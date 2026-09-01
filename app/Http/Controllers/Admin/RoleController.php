<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Permissiongroup;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use View;

class RoleController extends Controller
{
    public function index(){
        return view('Admin.Roles.index');
    }

    public function data(Request $request){
        $systemRoles = getSystemRoles();
        $query = Role::whereNotIn('name', $systemRoles);
        // $query = Role::where('id','!=',0);

        return DataTables::eloquent($query)
            ->editColumn('name', function ($role) {
                return $role->name;
            }) 
            ->editColumn('status', function ($role) {
                $checked = $role->status === 'ACTIVE' ? 'checked' : '';
                return '<div class="form-check form-switch"><input class="form-check-input role-status-switch" type="checkbox" '.$checked.' data-routekey="'.$role->route_key.'"/></div>';
            })
            ->addColumn('permissions', function ($role) {
                $permissions  = '<a href="'.route('admin.roles.permissions.show',['role' => $role->route_key]).'" class="btn btn-sm btn-info fs-1 ">Permissions &nbsp; <i class="ti ti-fingerprint"></i></a>';
                return $permissions;
            })
            ->addColumn('action', function ($role) {
                $edit  = '<a href="'.route('admin.roles.edit',['role' => $role->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"></i> </a>';
                $show = '<a href="'.route('admin.roles.show',['role' => $role->route_key]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="roles" data-title="Role" data-route-key="'.$role->route_key.'"><i class="fa fa-eye"></i></a>';
                return $edit.' '.$show;
            })   
           ->addIndexColumn()
           ->rawColumns(['name','status','permissions','action'])->setRowId('id')->make(true);
    }

    public function list(){
		$roles = Role::whereNotIn('name',['SuperAdmin','Admin'])
            ->where('status', 'ACTIVE')
            ->get();
        return response()->json([   
            'status' => 'success',
            'list' => $roles
        ],200);   
	}

    public function create(){
        return view('Admin.Roles.form');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate($this->rules, $this->customMessages);

        $role = new Role;
        $role->fill($request->all());
        $role->save();

        return response()->json([   
            'status' => 'success',
            'message' => 'Role Updated Successfully',
            'role' => $role
        ],201);            
    }

    public function show(Role $role){
        return view('Admin.Roles.show',compact('role'));
    }

    public function edit($role){
        return View('Admin.Roles.form',compact('role'));
    }

    public function update(Request $request,$role){
        $this->rules['name'] = 'required|regex:/^[\pL\s\-]+$/u|unique:roles,name,'.$role->id;
        $request->validate($this->rules, $this->customMessages);
        
        $role->fill($request->all());
        $role->save();

        return response()->json([   
            'status' => 'success',
            'message' => 'Role Updated Successfully',
            'role' => $role
        ],201);    
    }

    public function destroy(Role $role){
        
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'route_key' => 'required',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $role = Role::findByKey($request->route_key);

        if (! $role || in_array($role->name, getSystemRoles(), true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This role cannot be updated.',
            ], 422);
        }

        if ($request->status === 'INACTIVE') {
            $assignedUsersCount = $role->users()->count();

            if ($assignedUsersCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "This role is assigned to {$assignedUsersCount} user(s). Please rotate/remove this role before deactivation.",
                ], 422);
            }
        }

        $role->status = $request->status;
        $role->save();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'status' => 'success',
            'message' => $role->name.' has marked '.$role->status.' successfully',
            'role' => $role,
        ], 201);
    }

    public function permissionsShow(Role $role){
        if(\Auth::user()->hasrole('SuperAdmin')){
            $permissions_groups = Permissiongroup::whereNotIn('name',['PermissionGroup','Permission'])->get();
        }else{
            $permissions_groups = Permissiongroup::whereNotIn('name',['PermissionGroup','Permission','Crud','User'])->get();
        }
        
        $permissions = Permission::all();
        $role_permissions = $role->getAllPermissions();

        return view('Admin.Roles.permissions',compact('role','permissions','permissions_groups','role_permissions'));
    }

    public function permissionsUpdate(Request $request,Role $role){
        $role->syncPermissions($request->permissions);
        $users = User::role($role->name)->get();
        if(isset($users)){
            foreach($users as $user){
                $user->syncPermissions($request->permissions);
            }
        }
        return response()->json([   
            'status' => 'success',
            'message' => 'Permissions Updated Successfully',
        ],201);    
    }

    private $rules = [
        'name' => 'required|regex:/^[\pL\s\-]+$/u|unique:roles',
        'countries' => 'required', // Add rule for countries
    ];

    private $customMessages = [
        'name.required' => 'Please enter role',
        'name.unique' => 'The role has already been taken',
        'name.regex:/^[\pL\s\-]+$/u' => 'The role should be in letter format',
        'countries.required' => 'Please select at least one country', // Add custom message for countries
    ];
}
