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
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isadmin == 1){
            $permission_types = Permissiontype::paginate(10);
            return view('permissions',compact('permission_types'));
        }
        else{
            return abort('403');
        }
    }
    public function permission(Request $request)
    {
        if (Permission::where('user_id', '=', $request->user_id)->exists()) {
            Permission::where('user_id', $request->user_id)->delete();
        }
        foreach($request->role_id as $index => $role_id) {
            $data = new Permission();
            $data->user_id = $request->user_id;
            $data->role_id = $request->role_id[$index];
            $data->save();
        }
        return redirect()->back()->with('success', true);
    }
}
