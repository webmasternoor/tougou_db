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

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $permission_types = Permissiontype::join('policies', 'policies.type_id', '=', 'permissiontypes.id')
        ->join('permissions', 'permissions.role_id', '=', 'policies.role_id')
        ->where('permissions.user_id','=', Auth::user()->id)
        ->get(['permissiontypes.*', 'permissions.user_id']);

        if(Auth::user()->isadmin == 1){
            $roles = Role::paginate(10);
            $permission_types = Permissiontype::get();
            return view('roles.roles',compact('roles','permission_types'));
        }
        else{
            return abort('403');
        }
    }
}
