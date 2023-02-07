<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Imports\UsersImport;
use App\Models\Address1;
use App\Models\DesiredWorkStyle;
use App\Models\Message;
use App\Models\TougouUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Import;
// use Maatwebsite\Excel\Facades\Excel;

use App\Models\ImportUser;
use App\Models\LineMessageSends;
use App\Models\Permissiontype;
use App\Models\Subject;
use App\Models\TougouUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
// use Auth;
use Illuminate\Support\Facades\Auth;

class TougouMessagesController extends Controller
{
    function getRolePermission()
    {
        $permission_types = Permissiontype::join('policies', 'policies.type_id', '=', 'permissiontypes.id')
            ->join('permissions', 'permissions.role_id', '=', 'policies.role_id')
            ->where('permissions.user_id', '=', Auth::user()->id)
            ->get(['permissiontypes.*', 'policies.type_id as type_id', 'permissions.user_id as user_id', 'permissions.role_id as role_id']);

        return $permission_types;
    }

    public function index()
    {
        // $address1 = Address1::select('name')->distinct()->get();
        // $college_name = TougouUserInfo::select('id', 'college_name')->distinct()->where('college_name', '!=', '')->get();
        // $desired_work_styles = DesiredWorkStyle::select('id', 'name')->get();

        // $kiboukamoku = Subject::select('name')->distinct()->get();

        // return view('message_send', ["address1" => $address1, "college_name" => $college_name, "desired_work_styles" => $desired_work_styles, "kiboukamoku" => $kiboukamoku]);

        // dd('test');
        $permission_types = $this->getRolePermission();
        if (Auth::user()->isadmin == 1 || $permission_types['0']['role_id'] == 2 || $permission_types['0']['role_id'] == 3) {
            $address1 = Address1::select('name')->distinct()->get();
            $college_name = TougouUserInfo::select('id','college_name')->distinct()->where('college_name','!=','')->get();
            $desired_work_styles = DesiredWorkStyle::select('id','name')->get();
            $kiboukamoku = Subject::select('name')->distinct()->get();
            return view('message_send', ["address1" => $address1, "college_name" => $college_name, "desired_work_styles" => $desired_work_styles, "kiboukamoku" => $kiboukamoku]);
        }
        else{
            return abort('403');
        }
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required',
            'messages' => 'required',
        ]);

        foreach ($request->user_id as $index => $user_id) {
            $aa = new Message();
            $aa->user_id = $request->user_id[$index];
            $aa->messages = $request->messages;
            $aa->save();
        }
        return view('remoteusers');
    }

    public function sendRequest(Request $request)
    {
        

        if ($request->desired_work_style) {
            $desired_working_j = '';
            $desired_working_h = '';
            $desired_working_s = '';
            $desired_working_k = '';
            if ($request->desired_work_style) {
                foreach ($request->desired_work_style as $val) {
                    if ($val == '常勤') {
                        $desired_working_j = '1';
                    }
                    if ($val == '非常勤') {
                        $desired_working_h = '1';
                    }
                    if ($val == 'スポット') {
                        $desired_working_s = '1';
                    }
                    if ($val == '健診') {
                        $desired_working_k = '1';
                    }
                }
            }
        }

        $grad_year = TougouUserInfo::selectRaw(" MIN(graduation_year) AS StartFrom, MAX(graduation_year) AS EndTo")
            ->where([['graduation_year', '>', 0]])
            ->first();

        $from_year_db = $grad_year['StartFrom'];
        $to_year_db = $grad_year['EndTo'];

        $query = DB::table('tougou_user_infos AS a')
            ->leftJoin('tougou_user_social_logins AS b', 'a.user_id', '=', 'b.user_id')
            ->leftJoin('tougou_users AS c', 'a.user_id', '=', 'c.id')
            ->select(
                'c.email',
                'a.user_id',
                'a.lastname',
                'a.user_id',
                'a.firstname',
                'a.firstname_kana',
                'a.lastname_kana',
                'a.sex',
                'a.address1',
                'a.address2',
                'a.graduation_year',
                'a.college_name',

                'a.desired_working_j',
                'a.desired_working_h',
                'a.desired_working_s',
                'a.desired_working_k',

                'a.j_subject',
                'a.h_subject',
                'a.s_subject',

                'a.j_subject_others',
                'a.h_subject_others',
                'a.s_subject_others',

                'a.j_location1',
                'a.j_location2',
                'a.j_location3',
                'a.h_location1',
                'a.h_location2',
                'a.h_location3',
                'a.s_location1',
                'a.s_location2',
                'a.s_location3',
                'a.k_location1',
                'a.k_location2',
                'a.k_location3',
                
                'a.kiboukamoku',

                'b.line_id',
                'b.socialplus_id'
            );

        if ($request->has('pref')) {
            $q = $request->get('pref');
            $query->whereIn('a.address1', $q)
                ->orWhereIn('a.address2', $q);
        }


        if ($request->has('gender')) {
            $query->orWhere('a.sex', $request->get('gender'));
        }

        // if ($request->has('college_name')) {
        //     $query->orWhereIn('a.college_name', $request->get('college_name'));
        // }

        if ($request->has('grad_year_from2') || $request->has('grad_year_to2')) {

            if ($request['grad_year_from2'] != null && $request['grad_year_to2'] != null) {
                $query->WhereBetween('a.graduation_year', [$request['grad_year_from2'], $request['grad_year_to2']]);
            } else if ($request['grad_year_from2'] != null) {
                $query->WhereBetween('a.graduation_year', [$request['grad_year_from2'], $to_year_db]);
            } else if ($request['grad_year_to2'] != null) {
                $query->WhereBetween('a.graduation_year', [$from_year_db, $request['grad_year_to2']]);
            }
        }

        if ($request->has('kiboukamoku')) {
            $q = $request->get('kiboukamoku');
            $query->whereIn('a.j_subject', $q)
                ->orWhereIn('a.j_subject_others', $q)
                ->orWhereIn('a.h_subject', $q)
                ->orWhereIn('a.h_subject_others', $q)
                ->orWhereIn('a.s_subject', $q)
                ->orWhereIn('a.s_subject_others', $q)
                ->orWhereIn('a.kiboukamoku', $q);
        }

        if ($request->has('preferred_work_place')) {
            $q = $request->get('preferred_work_place');
            $query->whereIn('a.j_location1', $q)
                ->orWhereIn('a.j_location2', $q)
                ->orWhereIn('a.j_location3', $q)
                ->orWhereIn('a.h_location1', $q)
                ->orWhereIn('a.h_location2', $q)
                ->orWhereIn('a.h_location3', $q)
                ->orWhereIn('a.s_location1', $q)
                ->orWhereIn('a.s_location2', $q)
                ->orWhereIn('a.s_location3', $q)
                ->orWhereIn('a.k_location1', $q)
                ->orWhereIn('a.k_location2', $q)
                ->orWhereIn('a.k_location3', $q);
        }

        if ($request->has('desired_working_j')) {
            $query->orWhere('a.desired_working_j', $request->get('desired_working_j'));
        }

        if ($request->has('desired_working_h')) {
            $query->orWhere('a.desired_working_h', $request->get('desired_working_h'));
        }

        if ($request->has('desired_working_s')) {
            $query->orWhere('a.desired_working_s', $request->get('desired_working_s'));
        }

        if ($request->has('desired_working_k')) {
            $query->orWhere('a.desired_working_k', $request->get('desired_working_k'));
        }
        // $query->whereNotNull('b.line_id')->orWhere('b.line_id','<>','');

        $records = $query->get();

        $permission_types = $this->getRolePermission();
        if (Auth::user()->isadmin == 1 || $permission_types['0']['role_id'] == 2 || $permission_types['0']['role_id'] == 3) {
            $histories = DB::table('line_message_sends')
                ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
                ->where('users.mgt_no', '=', Auth::user()->mgt_no)
                ->select('line_message_sends.*', 'users.*', 'users.family_name', 'users.first_name', 'line_message_sends.created_at')
                ->orderBy('line_message_sends.created_at', 'DESC')
                ->get();
        } else {
            return abort(403);
        }

        return view('message_send_success', compact('records', 'histories'));
    }

    public function sendMessage(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'message' => 'required',
        ]);

        $sender_id = User::find(auth()->user()->id);
        $data = new LineMessageSends;
        $data->user_id = json_encode($request->user_id);
        $data->sender_id = $sender_id->mgt_no;
        $data->message = $request->message;
        $data->save();
        $records = $request->only('user_id', 'message');
        $newRecords = collect($records['user_id'])->map(function ($record) use ($records) {
            $record = TougouUserInfo::where('user_id', $record)->first();
            $record = [
                "name" => $record->lastname,
                "message" => $records['message']
            ];
            return $record;
        });
        return response()->json($newRecords);
    }

    public function loadIntegratedUsers(Request $request)
    {
        $data = TougouUser::select('*')
            ->with(['user_infos' => function ($query) {
                $query->select('user_id', 'id', 'firstname', 'lastname');
            }])
            ->whereIn('id', $request->ids)
            ->get();

        return response()->json($data);
    }

    public function showmessage(Request $request)
    {
        $messages = [];

        if ($request->exists("keywords.submitted_at") && $request->keywords['submitted_at'] != '') {
            $messages = DB::table('line_message_sends')
                ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
                ->whereDate('line_message_sends.created_at', '=', $request->keywords['submitted_at'])
                ->select('line_message_sends.*', 'users.*', 'users.family_name', 'users.first_name', 'line_message_sends.created_at')
                ->get();
        }

        if ($request->exists("keywords.mgt_no") && $request->keywords['mgt_no'] != '') {

            $messages = DB::table('line_message_sends')
                ->join('users', 'line_message_sends.sender_id', '=', 'users.mgt_no')
                ->select('line_message_sends.*', 'users.*', 'users.family_name', 'users.first_name', 'line_message_sends.created_at')
                ->where('line_message_sends.sender_id', 'LIKE', '%' . $request->keywords['mgt_no'] . '%')
                ->get();
        }

        return response()->json(['messages' => $messages]);
    }
}
