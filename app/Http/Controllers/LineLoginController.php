<?php

namespace App\Http\Controllers;

use App\Models\TougouUser;
use App\Models\TougouUserSocialLogin;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class LineLoginController extends Controller
{
    public function login()
    {
        $socailPlusUrl = "https://8b0f6aa7f1.auth.socialplus.jp/linkstaff/e-resident/line/authenticate?callback=" . route('login.line.check', ['previous_url' => URL::previous()]) . "&callback_if_failed=http://localhost:8002/error";
        return redirect($socailPlusUrl);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Client\Response
     */
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

            $socailUser = json_decode($response->getBody(), true);  // status, user[identifier, primary_key, mapped_at, last_logged_in_at, login_count, profile_prohibited, created_at, last_logged_in_provider]
            $uniqueSocialPlusId = $socailUser['user']['identifier'];

            $userResponse = $client->request('GET', 'https://api.socialplus.jp/api/user_attribute', [
                'form_params' => [
                    'key' => Config::get('app.social.line.social_plus_api_key'),
                    'identifier' =>  $uniqueSocialPlusId
                ]
            ]);

            $lineinfo = json_decode((string) $userResponse->getBody(), true);  // status, user[identifier, primary_key, mapped_at, last_logged_in_at, login_count, profile_prohibited, created_at, last_logged_in_provider], providers[line[line_id, access_token, refresh_token, expires_at, friendship_updated_at, updated_at, friendship_status, friendship_status_updated_at]]
            $lineId = $lineinfo['providers']['line']['line_id'];
        } catch (\Exception $e) {
            return response()->json('', 400);
        }

        $integratedUser = TougouUserSocialLogin::where('line_id', $lineId)
            ->orWhere('socialplus_id', $uniqueSocialPlusId)
            ->first();

        if ($integratedUser) {
        } else {
            $tougouDbUser = TougouUser::updateOrCreate([
                'email' => $lineinfo['user']['primary_key'],
                'password' => Hash::make('12345678'),
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
        
        if ($request->exists('previous_url') && $request->previous_url != '') {
            
            // $params = array_merge($request->only(['status']), $lineinfo);
            return redirect()->away($request->previous_url . "user/line/check" . '?' . http_build_query($lineinfo));
            // dd($lineinfo);
        }

        return response()->json(['end_point' => true], 200);
    }
}
