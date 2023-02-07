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

class PolicyController extends Controller
{
    public function index(Request $request)
    {

    }

    public function store(Request $request)
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
}
