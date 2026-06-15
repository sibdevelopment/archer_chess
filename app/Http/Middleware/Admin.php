<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('admin/employee/camera-check')) {
            return $next($request);
        }
        if(!\Auth::user()){
            \Auth::logout();
        }
        if(\Auth::user()->status != 'ACTIVE'){
            \Auth::logout();
            return \Redirect::back()->withErrors(['You are not allowed to view this page. Please contact with admin.']);
        }
        $allow_user = false;
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = str_replace('App\Http\Controllers\\','',$controller);

        $permissions = \Auth::user()->getAllPermissions()->pluck('id');
        // dd($permissions);
        $permissions = \App\Models\Permission::whereIn('id',$permissions)->get();
        foreach($permissions as $permission){
            if($permission->permissiongroup->controller == $controller){
                foreach($permission->methods as $permission_method){
                    if($permission_method == $method){
                        $allow_user = true;
                    }
                }
            }
        }
        if($allow_user){
            return $next($request);
        }else{
            return abort(403);
        }

        return $next($request);
    }
}
