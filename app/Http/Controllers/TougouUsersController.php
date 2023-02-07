<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Permissiontype;
use App\Models\Policy;
use App\Models\Role;
use App\Models\TougouUser;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TougouUsersController extends Controller
{
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view('profile',compact('user'));
    }
    public function message_send()
    {
        // $user = User::find(Auth::user()->id);
        $user = DB::table('tougou_users')
                ->join('tougou_user_infos', 'tougou_user_infos.user_id', '=', 'tougou_users.id')
                ->join('tougou_user_social_logins', 'tougou_user_social_logins.user_id', '=', 'tougou_users.id')
                ->get();
        return view('message_send',compact('user'));
    }

}
