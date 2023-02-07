<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Create;
use App\Http\Requests\User\Update;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Crud\Crud;
use App\Http\Requests\Import;
use App\Imports\ImportUser;
use App\Imports\UsersImport;
use App\Models\Permission;
use App\Models\Permissiontype;
use App\Models\Policy;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function getRolePermission()
    {
        $permission_types = Permissiontype::join('policies', 'policies.type_id', '=', 'permissiontypes.id')
            ->join('permissions', 'permissions.role_id', '=', 'policies.role_id')
            ->where('permissions.user_id', '=', Auth::user()->id)
            ->get(['permissiontypes.*', 'policies.type_id as type_id','permissions.user_id as user_id', 'permissions.role_id as role_id']);

        return $permission_types;
    }
    public function index(Request $request)
    {
        if(Auth::user()->isadmin == 1){
            $users = User::paginate(10);
            $roles = Role::get();
            return view('users',compact('users','roles'));
        }
        else{
            return abort('403');
        }
    }

    public function policy(Request $request)
    {
        if (Policy::where('role_id', '=', $request->role_id)->exists()) {
            policy::where('role_id', $request->role_id)->delete();
        }
        foreach($request->type_id as $index => $type_id) {
            $data = new Policy();
            $data->role_id = $request->role_id;
            $data->type_id = $request->type_id[$index];
            $data->save();
        }
        return redirect()->back()->with('success', true);
    }
    public function roles(Request $request)
    {
        if(Auth::user()->isadmin == 1){
            $roles = Role::paginate(10);
            $permission_types = Permissiontype::get();
            return view('roles.roles',compact('roles','permission_types'));
        }
        else{
            return abort('403');
        }
    }
    public function show(Role $role)
    {
        // dd('test');
        return view('roles.show',compact('role'));
    }
    // public function permissions(Request $request)
    // {
    //     if(Auth::user()->isadmin == 1){
    //         $permission_types = Permissiontype::paginate(10);
    //         return view('permissions',compact('permission_types'));
    //     }
    //     else{
    //         return abort('403');
    //     }
    // }
    public function import()
    {
        try {
            Excel::import(new UsersImport,request()->file('file'));
            return back();
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
