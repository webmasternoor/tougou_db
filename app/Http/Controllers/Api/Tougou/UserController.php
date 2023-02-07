<?php

namespace App\Http\Controllers\Api\Tougou;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\Subject;
use App\Models\TougouUser;
use App\Models\TougouUserInfo;
use App\Models\TougouUserSocialLogin;
use App\Models\User;
use App\Models\UserSocialLogin;
use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function requestLogin(Request $request)
    {
        try {
            $user = TougouUser::where('users.email', $request->get('email'))
                ->with(['user_infos', 'user_social_logins'])
                ->first();

            if (empty($user)) {
                $responses = Http::pool(fn (Pool $pool) => [
                    $pool->get(Config::get('app.app1URL') . '/api/user/info/' .  $request->email),
                ]);

                $rs = $this->processDataInsert($this->processResponseData($responses));
            }

            return response(['success' => $rs], $rs ? 200 : 404);
        } catch (Exception $e) {
            return response(['success' => false], 404);
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bulkInsert()
    {
        try {
            DB::beginTransaction();

            $responses = Http::pool(fn (Pool $pool) => [
                $pool->get(Config::get('app.app1URL') . '/api/users/info'),
            ]);

            $rs = $this->processDataInsert($this->processResponseData($responses));
            DB::commit();
            return response(['success' => $rs], $rs ? 200 : 404);
        } catch (Exception $e) {
            return response(['success' => false], 404);
        }
    }

    /**
     * accept unified json responses
     *
     * @param [json] $responses
     * @return array
     */
    function processResponseData($responses): array
    {
        $data = [];
        foreach ($responses as $response) {
            $data = $response->json();
        }
        return $data;
    }

    /**
     * Accept list and insert to DB
     *
     * @param [list] $list
     * @return boolean
     */
    function processDataInsert($list): bool
    {
        if ($list) {
            foreach ($list as $data) {
                
                $tougouDbUser = TougouUser::updateOrCreate([                    
                    'email' => $data['email'],
                    'password' => $data['password']
                ]);
                
                if ($tougouDbUser->wasRecentlyCreated) {
                    $tougouDbUser->user_infos()->saveMany([
                        new TougouUserInfo([
                            'lastname' => $data['lastname'] ?? '',
                            'firstname' => $data['firstname'] ?? '',
                            'lastname_kana' => $data['lastname_kana'] ?? '',
                            'firstname_kana' => $data['firstname_kana'] ?? '',
                            'birthdate' => $data['birthdate'] ?? '',
                            'zip' => $data['zip'] ?? '',
                            'address1' => $data['address1'] ?? '',
                            'address2' => $data['address2'] ?? '',
                            
                            'college_type' => $data['college_type'] ?? '',
                            'college_name' => $data['college_name'] ?? '',
                            'graduation_year' => $data['graduation_year'] ?? '',
                            'medical_license_year' => $data['medical_license_year'] ?? '',
                            'medical_license_month' => $data['medical_license_month'] ?? '',
                            'medical_license_day' => $data['medical_license_day'] ?? '',
                            'desired_working_j' => $data['desired_working_j'] ?? '',
                            'desired_working_h' => $data['desired_working_h'] ?? '',
                            'desired_working_s' => $data['desired_working_s'] ?? '',
                            'desired_working_k' => $data['desired_working_k'] ?? '',
                            
                            'j_location1' => $data['j_location1'] ?? '',
                            'j_location2' => $data['j_location2'] ?? '',
                            'j_location3' => $data['j_location3'] ?? '',
                            'h_location1' => $data['h_location1'] ?? '',
                            'h_location2' => $data['h_location2'] ?? '',
                            'h_location3' => $data['h_location3'] ?? '',
                            's_location1' => $data['s_location1'] ?? '',
                            's_location2' => $data['s_location2'] ?? '',
                            's_location3' => $data['s_location3'] ?? '',
                            'k_location1' => $data['k_location1'] ?? '',
                            'k_location2' => $data['k_location2'] ?? '',
                            'k_location3' => $data['k_location3'] ?? '',

                            'j_subject' => $data['j_subject'] ?? '',
                            'j_subject_others' => $data['j_subject_others'] ?? '',
                            'h_subject' => $data['h_subject'] ?? '',
                            'h_subject_others' => $data['h_subject_others'] ?? '',
                            's_subject' => $data['s_subject'] ?? '',
                            's_subject_others' => $data['s_subject_others'] ?? '',
                            
                            'doctor_id' => $data['doctor_id'] ?? '',
                            'birthplace' => $data['birthplace'] ?? '',
                            'sex' => $data['sex'] ?? '',
                            'kiboukamoku' => $data['kiboukamoku'] ?? '',
                            
                        ])
                    ]);

                    $tougouDbUser->user_social_logins()->saveMany([
                        new TougouUserSocialLogin([
                            'line_id' => $data['line_id'] ?? '',
                            'access_tocken' => $data['line_access_tocken'] ?? '',
                            'refresh_token' => $data['line_refresh_token'] ?? '',
                            'remember_token' => $data['line_remember_token'] ?? '',
                            'socialplus_id' => $data['line_socialplus_id'] ?? '',
                        ])
                    ]);
                } else {

                    TougouUserInfo::where('user_id', $tougouDbUser['id'])->update([
                        'lastname' => $data['lastname'] ?? '',
                        'firstname' => $data['firstname'] ?? '',
                        'lastname_kana' => $data['lastname_kana'] ?? '',
                        'firstname_kana' => $data['firstname_kana'] ?? '',
                        'birthdate' => $data['birthdate'] ?? '',
                        'zip' => $data['zip'] ?? '',
                        'address1' => $data['address1'] ?? '',
                        'address2' => $data['address2'] ?? '',
                        
                        'college_type' => $data['college_type'] ?? '',
                        'college_name' => $data['college_name'] ?? '',
                        'graduation_year' => $data['graduation_year'] ?? '',
                        'medical_license_year' => $data['medical_license_year'] ?? '',
                        'medical_license_month' => $data['medical_license_month'] ?? '',
                        'medical_license_day' => $data['medical_license_day'] ?? '',
                        'desired_working_j' => $data['desired_working_j'] ?? '',
                        'desired_working_h' => $data['desired_working_h'] ?? '',
                        'desired_working_s' => $data['desired_working_s'] ?? '',
                        'desired_working_k' => $data['desired_working_k'] ?? '',
                        
                        'j_location1' => $data['j_location1'] ?? '',
                        'j_location2' => $data['j_location2'] ?? '',
                        'j_location3' => $data['j_location3'] ?? '',
                        'h_location1' => $data['h_location1'] ?? '',
                        'h_location2' => $data['h_location2'] ?? '',
                        'h_location3' => $data['h_location3'] ?? '',
                        's_location1' => $data['s_location1'] ?? '',
                        's_location2' => $data['s_location2'] ?? '',
                        's_location3' => $data['s_location3'] ?? '',
                        'k_location1' => $data['k_location1'] ?? '',
                        'k_location2' => $data['k_location2'] ?? '',
                        'k_location3' => $data['k_location3'] ?? '',
                        'j_subject' => $data['j_subject'] ?? '',
                        'j_subject_others' => $data['j_subject_others'] ?? '',
                        'h_subject' => $data['h_subject'] ?? '',
                        'h_subject_others' => $data['h_subject_others'] ?? '',
                        's_subject' => $data['s_subject'] ?? '',
                        's_subject_others' => $data['s_subject_others'] ?? '',
                        
                        'doctor_id' => $data['doctor_id'] ?? '',
                        'birthplace' => $data['birthplace'] ?? '',
                        'sex' => $data['sex'] ?? '',

                        'kiboukamoku' => $data['kiboukamoku'] ?? '',
                        
                    ]);


                    TougouUserSocialLogin::where('user_id', $tougouDbUser['id'])->update([
                        'line_id' => $data['line_id'] ?? '',
                        'access_tocken' => $data['line_access_tocken'] ?? '',
                        'refresh_token' => $data['line_refresh_token'] ?? '',
                        'remember_token' => $data['line_remember_token'] ?? '',
                        'socialplus_id' => $data['line_socialplus_id'] ?? '',
                    ]);
                }
            }

            return true;
        }

        return false;
    }


    public function bulkInsertd()
    {
        DB::beginTransaction();
        $jsonResponse = Http::get(Config::get('app.app2URL') . '/mypage/user/userrecords')->json();

        if ($jsonResponse) {

            foreach ($jsonResponse as $jsonSubject) {
                if ($jsonSubject) {

                    $delimiters = ['、', '・', '　', ' '];
                    $subject_multiple = array_filter(explode($delimiters[0], str_replace($delimiters, $delimiters[0], $jsonSubject['j_subject'])), 'strlen');               

                    foreach ($subject_multiple as $subject_single) {
                        Subject::create([
                            'name' =>  $subject_single
                        ]);
                    }
                }
            }

            foreach ($jsonResponse as $jsonUser) {
                $tougouDbUser = TougouUser::updateOrCreate(
                    [
                        'email' => $jsonUser['email'],
                        'password' => $jsonUser['password']
                    ],
                );

                if ($tougouDbUser->wasRecentlyCreated) {
                    $tougouDbUser->user_infos()->saveMany([
                        new TougouUserInfo([

                            'lastname' => $jsonUser['lastname'] ?? '',
                            'firstname' => $jsonUser['firstname'] ?? '',
                            'lastname_kana' => $jsonUser['lastname_kana'] ?? '',
                            'firstname_kana' => $jsonUser['firstname_kana'] ?? '',
                            'birthdate' => date('Y-m-d H:i:s', strtotime($jsonUser['birthdate']))  ?? '',
                            'zip' => $jsonUser['zip'] ?? '',
                            'address1' => $jsonUser['address1'] ?? '',
                            'address2' => $jsonUser['address2'] ?? '',
                            'college_type' => $jsonUser['college_type'] ?? '',
                            'college_name' => $jsonUser['college_name'] ?? '',
                            'graduation_year' => $jsonUser['graduation_year'] ?? '',
                            'medical_license_year' => $jsonUser['medical_license_year'] ?? '',
                            'medical_license_month' => $jsonUser['medical_license_month'] ?? '',
                            'medical_license_day' => $jsonUser['medical_license_day'] ?? '',
                            'desired_working_j' => $jsonUser['desired_working_j'] ?? '',
                            'desired_working_h' => $jsonUser['desired_working_h'] ?? '',
                            'desired_working_s' => $jsonUser['desired_working_s'] ?? '',
                            'desired_working_k' => $jsonUser['desired_working_k'] ?? '',
                            'j_location1' => $jsonUser['j_location1'] ?? '',
                            'j_location2' => $jsonUser['j_location2'] ?? '',
                            'j_location3' => $jsonUser['j_location3'] ?? '',
                            'h_location1' => $jsonUser['h_location1'] ?? '',
                            'h_location2' => $jsonUser['h_location2'] ?? '',
                            'h_location3' => $jsonUser['h_location3'] ?? '',
                            's_location1' => $jsonUser['s_location1'] ?? '',
                            's_location2' => $jsonUser['s_location2'] ?? '',
                            's_location3' => $jsonUser['s_location3'] ?? '',
                            'k_location1' => $jsonUser['k_location1'] ?? '',
                            'k_location2' => $jsonUser['k_location2'] ?? '',
                            'k_location3' => $jsonUser['k_location3'] ?? '',
                            'j_subject' => $jsonUser['j_subject'] ?? '',
                            'j_subject_others' => $jsonUser['j_subject_others'] ?? '',
                            'h_subject' => $jsonUser['h_subject'] ?? '',
                            'h_subject_others' => $jsonUser['h_subject_others'] ?? '',
                            's_subject' => $jsonUser['s_subject'] ?? '',
                            's_subject_others' => $jsonUser['s_subject_others'] ?? '',
                            'doctor_id' => $jsonUser['doctor_id'] ?? '',
                            'birthplace' => $jsonUser['birthplace'] ?? '',
                            'sex' => $jsonUser['sex'] ?? '',
                            'kiboukamoku' => $jsonUser['kiboukamoku'] ?? '',
                        ])
                    ]);
                }
            }
        }

        DB::commit();
        return response()->json(['success' => true], 200);
    }

    // doctorsgate start

    public function bulkInsertdg()
    {
        DB::beginTransaction();

        // -------------------  Finding Duplicate and Merging ----------------------------------
        $jsonResponse = Http::timeout(0)->withoutVerifying()->get(Config::get('app.app3URL') . '/admin/profile/member_list1.php');
        $json_array = json_decode($jsonResponse, TRUE);

        // dd($jsonResponse);
        $jsonResponse = array();
        $exists    = array();
        foreach ($json_array as $element) {
            if (!in_array(strtolower($element['email']), $exists)) {
                $jsonResponse[] = $element;
                $exists[]    = $element['email'];
            }
        }
        // $jsonResponse = json_encode( $jsonResponse );
        echo json_encode($jsonResponse);
        // -------------------  Finding Duplicate and Merging ---------------------------------------

        if ($jsonResponse) {
            foreach ($jsonResponse as $jsonUser) {

                $tougouDbUser = TougouUser::updateOrCreate(
                    [
                        'email' => $jsonUser['email'] ?? '',
                        'password' => $jsonUser['password']
                    ]
                );

                if ($tougouDbUser->wasRecentlyCreated) {
                    $tougouDbUser->user_infos()->saveMany([
                        new TougouUserInfo([
                            'lastname' => $jsonUser['lastname'] ?? '',
                            'firstname' => $jsonUser['firstname'] ?? '',
                            'lastname_kana' => $jsonUser['lastname_kana'] ?? '',
                            'firstname_kana' => $jsonUser['firstname_kana'] ?? '',
                            'birthdate' => $jsonUser['birthdate'] ?? '',
                            'zip' => $jsonUser['zip'] ?? '',
                            'address1' => $jsonUser['address1'] ?? '',
                            'address2' => $jsonUser['address2'] ?? '',
                            'college_type' => $jsonUser['college_type'] ?? '',
                            'college_name' => $jsonUser['college_name'] ?? '',
                            'graduation_year' => $jsonUser['graduation_year'] ?? '',
                            'medical_license_year' => $jsonUser['medical_license_year'] ?? '',
                            'medical_license_month' => $jsonUser['medical_license_month'] ?? '',
                            'medical_license_day' => $jsonUser['medical_license_day'] ?? '',
                            'desired_working_j' => $jsonUser['desired_working_j'] ?? '',
                            'desired_working_h' => $jsonUser['desired_working_h'] ?? '',
                            'desired_working_s' => $jsonUser['desired_working_s'] ?? '',
                            'desired_working_k' => $jsonUser['desired_working_k'] ?? '',
                            'j_location1' => $jsonUser['j_location1'] ?? '',
                            'j_location2' => $jsonUser['j_location2'] ?? '',
                            'j_location3' => $jsonUser['j_location3'] ?? '',
                            'h_location1' => $jsonUser['h_location1'] ?? '',
                            'h_location2' => $jsonUser['h_location2'] ?? '',
                            'h_location3' => $jsonUser['h_location3'] ?? '',
                            's_location1' => $jsonUser['s_location1'] ?? '',
                            's_location2' => $jsonUser['s_location2'] ?? '',
                            's_location3' => $jsonUser['s_location3'] ?? '',
                            'k_location1' => $jsonUser['k_location1'] ?? '',
                            'k_location2' => $jsonUser['k_location2'] ?? '',
                            'k_location3' => $jsonUser['k_location3'] ?? '',
                            'j_subject' => $jsonUser['j_subject'] ?? '',
                            'j_subject_others' => $jsonUser['j_subject_others'] ?? '',
                            'h_subject' => $jsonUser['h_subject'] ?? '',
                            'h_subject_others' => $jsonUser['h_subject_others'] ?? '',
                            's_subject' => $jsonUser['s_subject'] ?? '',
                            's_subject_others' => $jsonUser['s_subject_others'] ?? '',
                            'doctor_id' => $jsonUser['doctor_id'] ?? '',
                            'birthplace' => $jsonUser['birthplace'] ?? '',
                            'sex' => $jsonUser['sex'] ?? '',
                            'kiboukamoku' => $jsonUser['kiboukamoku'] ?? '',
                        ])
                    ]);
                }
            }
        }

        DB::commit();
        return response()->json(['success' => true], 200);
    }
    /**
     * accept unified json responses
     *
     * @param [json] $responses
     * @return array
     */
    function processResponseDatadg($responses): array
    {
        $data = [];
        foreach ($responses as $response) {
            $data = $response->json();
        }
        return $data;
    }

    /**
     * Accept list and insert to DB
     *
     * @param [list] $list
     * @return boolean
     */
    function processDataInsertdg($list): bool
    {
        if ($list) {
            foreach ($list as $data) {
                $tougouDbUser = TougouUser::updateOrCreate([
                    'email' => $data['email'],
                    'password' => $data['password']
                ]);

                if ($tougouDbUser->wasRecentlyCreated) {
                    $tougouDbUser->user_infos()->saveMany([
                        new TougouUserInfo([
                            'lastname' => $data['lastname'] ?? '',
                            'firstname' => $data['firstname'] ?? '',
                            'lastname_kana' => $data['lastname_kana'] ?? '',
                            'firstname_kana' => $data['firstname_kana'] ?? '',
                            'birthdate' => $data['birthdate'] ?? '',
                            'zip' => $data['zip'] ?? '',
                            'address1' => $data['address1'] ?? '',
                            'address2' => $data['address2'] ?? '',
                        ])
                    ]);

                    $tougouDbUser->user_social_logins()->saveMany([
                        new TougouUserSocialLogin([
                            'line_id' => $data['line_id'] ?? '',
                            'access_tocken' => $data['line_access_tocken'] ?? '',
                            'refresh_token' => $data['line_refresh_token'] ?? '',
                            'remember_token' => $data['line_remember_token'] ?? '',
                            'socialplus_id' => $data['line_socialplus_id'] ?? '',
                        ])
                    ]);
                } else {

                    TougouUserInfo::where('user_id', $tougouDbUser['id'])->update([
                        'lastname' => $data['lastname'] ?? '',
                        'firstname' => $data['firstname'] ?? '',
                        'lastname_kana' => $data['lastname_kana'] ?? '',
                        'firstname_kana' => $data['firstname_kana'] ?? '',
                        'birthdate' => $data['birthdate'] ?? '',
                        'zip' => $data['zip'] ?? '',
                        'address1' => $data['address1'] ?? '',
                        'address2' => $data['address2'] ?? '',
                    ]);


                    TougouUserSocialLogin::where('user_id', $tougouDbUser['id'])->update([
                        'line_id' => $data['line_id'] ?? '',
                        'access_tocken' => $data['line_access_tocken'] ?? '',
                        'refresh_token' => $data['line_refresh_token'] ?? '',
                        'remember_token' => $data['line_remember_token'] ?? '',
                        'socialplus_id' => $data['line_socialplus_id'] ?? '',
                    ]);
                }
            }

            return true;
        }

        return false;
    }

    // doctorsgate end

    // line id using - data fetch as json
    public function getUserInfoByEmail($email)
    {
        $user = DB::table('tougou_users')
            ->join('tougou_user_infos', 'tougou_user_infos.user_id', '=', 'tougou_users.id')
            ->join('tougou_user_social_logins', 'tougou_user_social_logins.user_id', '=', 'tougou_users.id')
            ->where('email', $email)
            ->first();

        if ($user == null) {
            return abort(404);
        }

        $mappedUser = [
            [
                'email' => $user->email,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'lastname_kana' => $user->lastname_kana,
                'firstname_kana' => $user->firstname_kana,
                'birthdate' => $user->birthdate,
                'zip' => $user->zip,
                'address1' => $user->address1,
                'address2' => $user->address2,
                'line_id' => $user->line_id,
                'access_tocken' => $user->access_tocken,
                'refresh_token' => $user->refresh_token,
                'remember_token' => $user->remember_token,
                'socialplus_id' => $user->socialplus_id,
            ]
        ];

        return response($mappedUser, 200);
    }

    // line id using - data fetch as json


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = TougouUser::with(['user_infos', 'user_social_logins'])->get();
        return response($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserInfoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserInfoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TougouUserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function show(TougouUserInfo $userInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TougouUserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(TougouUserInfo $userInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserInfoRequest  $request
     * @param  \App\Models\TougouUserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserInfoRequest $request, TougouUserInfo $userInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TougouUserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(TougouUserInfo $userInfo)
    {
        //
    }
}
