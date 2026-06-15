<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use View;


class UserController extends Controller
{
    public function index(){
        return view('Admin.Users.index');
    }

    public function data(Request $request){
        $systemRoles = getSystemRoles();
        $query = User::whereHas("roles", function($q) use($systemRoles){ $q->whereIn("name", $systemRoles)->where('name','!=','SuperAdmin'); });

        return DataTables::eloquent($query)
            ->editColumn('first_name', function ($user) {
                return $user->first_name;
            }) 
            ->editColumn('last_name', function ($user) {
                return $user->last_name;
            }) 
            ->editColumn('email', function ($user) {
                return $user->email;
            }) 
            ->editColumn('mobile', function ($user) {
                return $user->mobile;
            }) 
            ->editColumn('status', function ($user) {
                if($user->status == 'ACTIVE'){
                    return '<div class="form-check form-switch"><input class="form-check-input user-status-switch" type="checkbox" checked data-routekey="'.$user->route_key.'"/></div>';
                }else{
                    return '<div class="form-check form-switch"><input class="form-check-input user-status-switch" type="checkbox" data-routekey="'.$user->route_key.'"/></div>';
                }
            })
            ->addColumn('action', function ($user) {
                $show = '<a href="'.route('admin.users.show',['user' => $user->route_key]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="users" data-title="User" data-route-key="'.$user->route_key.'"><i class="fa fa-eye"></i></a>';
                return $show;
            })   
           ->addIndexColumn()
           ->rawColumns(['first_name','last_name','email','mobile','status','action'])->setRowId('id')->make(true);
    }

    public function list(){
		$users = User::all();
        return response()->json([   
            'status' => 'success',
            'list' => $users
        ],200);   
	}

    // public function create(){
    //     $systemRoles = getSystemRoles();
    //     $roles = Role::whereNotIn('name', $systemRoles)->get();
    //     return view('Admin.Users.form',compact('roles'));
    // }

    // public function store(Request $request){
    //     $request->validate($this->rules, $this->customMessages);

    //     $user = new User;
    //     $user->fill($request->all());
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     $permissions = [];
    //     $user->assignRole($request->roles);
	// 	foreach($request->roles as $role_name){
	// 		$role = Role::where('name',$role_name)->first();
	// 		array_push($permissions,$role->permissions()->get());
	// 	}
	// 	$user->syncPermissions($permissions);

    //     return response()->json([   
    //         'status' => 'success',
    //         'message' => 'User Updated Successfully',
    //         'user' => $user
    //     ],201);            
    // }

    public function show(User $user){
        return view('Admin.Users.show',compact('user'));
    }

    // public function edit(User $user){
    //     $systemRoles = getSystemRoles();
    //     $roles = Role::whereNotIn('name', $systemRoles)->get();
    //     return View('Admin.Users.form',compact('user','roles'));
    // }

    // public function update(Request $request,$user){
    //     $this->rules['email'] = 'required|email|unique:users,email,'.$user->id;
    //     $this->rules['mobile'] = 'required|digits:10|unique:users,mobile,'.$user->id;
    //     $this->rules['password'] = 'nullable|min:6';
    //     $this->rules['password_confirmation'] = 'nullable|same:password';
    //     $request->validate($this->rules, $this->customMessages);
        
    //     $user->fill($request->all());
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     $permissions = [];
    //     $user->syncRoles([]);
    //     $user->assignRole($request->roles);
	// 	foreach($request->roles as $role_name){
	// 		$role = Role::where('name',$role_name)->first();
	// 		array_push($permissions,$role->permissions()->get());
	// 	}
	// 	$user->syncPermissions($permissions);

    //     return response()->json([   
    //         'status' => 'success',
    //         'message' => 'User Updated Successfully',
    //         'user' => $user
    //     ],201);    
    // }

    public function destroy(User $user){
        
    }

    public function changeStatus(Request $request){
        $user = User::findByKey($request->route_key);
        $user->status = $request->status;
        $user->save();

        return response()->json([   
            'status' => 'success',
            'message' => $user->first_name.' has marked '.$user->status.' successfully',
            'user' => $user
        ],201);    
    }

    private $rules = [
        'first_name' => 'required|regex:/^[\pL\s\-]+$/u',
        'last_name' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|digits:10|unique:users,mobile',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'roles' => 'required|min:1',
        'status' => 'required',
    ];
  
    private $customMessages = [
        'first_name.required' => 'First Name is required',
        'first_name.regex' => 'First Name should contain only alphabets',
        'last_name.required' => 'Last Name is required',
        'last_name.regex' => 'Last Name should contain only alphabets',
        'email.required' => 'Email is required',
        'email.email' => 'Email should be a valid email',
        'email.unique' => 'Email already exists',
        'mobile.required' => 'Mobile is required',
        'mobile.digits' => 'Mobile should be 10 digits',
        'mobile.unique' => 'Mobile already exists',
        'password.required' => 'Password is required',
        'password.min' => 'Password should be minimum 6 characters',
        'confirm_password.required' => 'Confirm Password is required',
        'confirm_password.same' => 'Confirm Password should be same as Password',
        'status.required' => 'Status is required',
    ];
}
