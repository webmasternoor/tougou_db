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

class RirekiController extends Controller
{
    
    function getRolePermission()
    {
        
        $permission_types = Permissiontype::join('policies', 'policies.type_id', '=', 'permissiontypes.id')
            ->join('permissions', 'permissions.role_id', '=', 'policies.role_id')
            ->where('permissions.user_id', '=', Auth::user()->id)
            ->get(['permissiontypes.*', 'policies.type_id as type_id', 'permissions.user_id as user_id', 'permissions.role_id as role_id']);

        return $permission_types;
    }

    public function index(Request $request)
    {
        $permission_types = $this->getRolePermission();
        if(Auth::user()->isadmin == 1){
            $histories = DB::table('line_message_sends')
            ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
            ->select('line_message_sends.*','users.*','users.family_name','users.first_name','line_message_sends.created_at')
            ->orderBy('line_message_sends.created_at','DESC')
            ->get();
        }
        if ($permission_types['0']['role_id'] == 2) {
            $histories = DB::table('line_message_sends')
            ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
            ->where('users.mgt_no','=', Auth::user()->mgt_no)
            ->select('line_message_sends.*','users.*','users.family_name','users.first_name','line_message_sends.created_at')
            ->orderBy('line_message_sends.created_at','DESC')
            ->get();
        }
        else if($permission_types['0']['role_id'] == 3){
            $histories = DB::table('line_message_sends')
            ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
            ->select('line_message_sends.*','users.*','users.family_name','users.first_name','line_message_sends.created_at')
            ->orderBy('line_message_sends.created_at','DESC')
            ->get();
        }
        else{
            return abort(403);
        }
        return view('rireki',compact('histories'));
    }
}
