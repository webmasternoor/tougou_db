<?php

namespace App\Http\Controllers;

use App\Models\TougouUser;
use App\Models\TougouUserInfo;
use App\Models\TougouUserSocialLogin;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class LineLoginController extends Controller
{
    public function login()
    {
        $socailPlusUrl = "https://8b0f6aa7f1.auth.socialplus.jp/linkstaff/e-resident/line/authenticate?callback=" . route('login.line.check') . "&registration=true&callback_if_failed=http://localhost:8002/error&extended_items=email,phone,real_name,gender,birthdate,address";
        return redirect($socailPlusUrl);
    }

    public function check(Request $request)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://api.socialplus.jp/api/authenticated_user', [
                'form_params' => [
                    'key' => Config::get('app.social.line.social_plus_api_key'),
                    'token' => $request->token,
                    'preserve_token' => true
                ]
            ]);

            $socailUser = json_decode($response->getBody(), true);
            // dd($socailUser);
            $uniqueSocialPlusId = $socailUser['user']['identifier'];
            try {
                $client = new Client();
                $userRes = $client->request('GET', 'https://api.socialplus.jp/api/user_attribute', [
                    'form_params' => [
                        'key' => Config::get('app.social.line.social_plus_api_key'),
                        'identifier' =>  $uniqueSocialPlusId,

                    ]
                ]);
                // dd($userRes);
                $lineinfo = json_decode((string) $userRes->getBody(), true);
                // dd($lineinfo);
                $lineId = $lineinfo['providers']['line']['line_id'];
                // $email = $lineinfo['user']['primary_key'];
            } catch (\Exception $e) {
                return response()->json('', 400);
                // return view('2021_user.login',['lineId'=>[]]);
            }
        } catch (\Exception $e) {
            return response()->json('', 400);
        }
        // dd($lineinfo);

        // $integratedUser = TougouUserSocialLogin::where([['socialplus_id', $uniqueSocialPlusId], ['line_id', $lineId]])->first();
        $integratedUser = TougouUserSocialLogin::where('line_id', $lineId)
            ->orWhere('socialplus_id', $uniqueSocialPlusId)
            ->first();
        // dd($integratedUser);

        if ($integratedUser) {                  
            
            dd('You are already Registered');

            // DO LOGIN
            return redirect(Config::get('app.app1URL') . 'user/login')->with(['uniqueSocialPlusId' => $uniqueSocialPlusId]);
            // return response()->json($integratedUser);
        } else {

            $tougouDbUser = TougouUser::updateOrCreate([                    
                'email' => $lineinfo['user']['primary_key'],
                'password' => '123456',
            ]);
            
            if ($tougouDbUser->wasRecentlyCreated) {
                $tougouDbUser->user_infos()->saveMany([
                    new TougouUserInfo([
                        'lastname' => '',
                        'firstname' => '',
                        'lastname_kana' => '',
                        'firstname_kana' => '',
                        'birthdate' => '2023-02-03 16:52:51',
                        'zip' => '',
                        'address1' => '',
                        'address2' => '',
                        
                        'college_type' => '',
                        'college_name' => '',
                        'graduation_year' => '',
                        'medical_license_year' => '',
                        'medical_license_month' => '',
                        'medical_license_day' => '',
                        'desired_working_j' => '',
                        'desired_working_h' => '',
                        'desired_working_s' => '',
                        'desired_working_k' => '',
                        
                        'j_location1' => '',
                        'j_location2' => '',
                        'j_location3' => '',
                        'h_location1' => '',
                        'h_location2' => '',
                        'h_location3' => '',
                        's_location1' => '',
                        's_location2' => '',
                        's_location3' => '',
                        'k_location1' => '',
                        'k_location2' => '',
                        'k_location3' => '',

                        'j_subject' => '',
                        'j_subject_others' => '',
                        'h_subject' => '',
                        'h_subject_others' => '',
                        's_subject' => '',
                        's_subject_others' => '',
                        
                        'doctor_id' => '',
                        'birthplace' => '',
                        'sex' => '',
                        'kiboukamoku' => '',
                        
                    ])
                ]);

                $tougouDbUser->user_social_logins()->saveMany([
                    new TougouUserSocialLogin([
                        'line_id' => $lineinfo['providers']['line']['line_id'] ?? '',
                        'access_tocken' => $lineinfo['providers']['line']['access_token'] ?? '',
                        'refresh_token' => $lineinfo['providers']['line']['refresh_token'] ?? '',
                        'remember_token' => $lineinfo['providers']['line']['line_id'] ?? '',
                        'socialplus_id' => $socailUser['user']['identifier'] ?? '',
                    ])
                ]);
            }
            dd('Data has been added successfully!');            

            return redirect(Config::get('app.app1URL') . 'user/login')->with(['uniqueSocialPlusId' => $uniqueSocialPlusId]);

            // return response()->json($socailUser['user']);
        }
    }
}
