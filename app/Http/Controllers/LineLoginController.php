<?php

namespace App\Http\Controllers;

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
            dd('yes');
            // DO LOGIN
            return redirect(Config::get('app.app1URL') . 'user/login')->with(['uniqueSocialPlusId' => $uniqueSocialPlusId]);
            // return response()->json($integratedUser);
        } else {
            dd('no');
            // NEW MEMBER to add TougouUserSocialLogin

            return redirect(Config::get('app.app1URL') . 'user/login')->with(['uniqueSocialPlusId' => $uniqueSocialPlusId]);

            // return response()->json($socailUser['user']);
        }
    }
}
