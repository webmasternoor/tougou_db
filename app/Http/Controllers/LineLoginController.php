<?php

namespace App\Http\Controllers;

use App\Models\TougouUserSocialLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class LineLoginController extends Controller
{
    public function login()
    {
        $socailPlusUrl = "https://8b0f6aa7f1.auth.socialplus.jp/linkstaff/e-resident/line/authenticate?callback=" . route('login.line.check') . "&callback_if_failed=http://localhost:8002/error&extended_items=email,phone,real_name,gender,birthdate,address";
        return redirect($socailPlusUrl);
    }

    public function check(Request $request)
    {
        try {

            $response = Http::get("https://api.socialplus.jp/api/authenticated_user", [
                'form_params' => [
                    'key' => Config::get('app.social.line.social_plus_api_key'),
                    'token' => $request->token,
                    'preserve_token' => true
                ]
            ]);

            $socailUser = json_decode($response->getBody(), true);
            $uniqueSocialPlusId = $socailUser['user']['identifier'];
        } catch (\Exception $e) {

            return response()->json('', 400);
        }

        $lineId = '';
        $integratedUser = TougouUserSocialLogin::where([['socialplus_id', $uniqueSocialPlusId], ['line_id', $lineId]])->first();

        if ($integratedUser) {
            // DO LOGIN

            return response()->json($integratedUser);
        } else {
            // NEW MEMBER

            return response()->json($socailUser['user']);
        }
    }
}
