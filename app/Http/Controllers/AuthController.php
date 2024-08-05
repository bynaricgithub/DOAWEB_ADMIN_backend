<?php

namespace App\Http\Controllers;

use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;


class AuthController extends Controller
{
    public function index()
    {
        return response()->json([
            "status"    =>  "success",
            "data"      =>  Auth::user()
        ], 200);
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $validator = Validator::make(
            [
                'username' => $username,
                'password' => $password,
            ],
            [
                'username' => 'required',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return json_encode([
                'status'                    =>  'failure',
                'message'                     =>     $validator->errors()->first(),
            ], 200);
        }

        $user_data = array(
            'username'          => $username,
            'password'             => $password,
            'status'             => '1',
            'role'                => 'ADMIN'
        );

        if (Auth::attempt($user_data)) {
            $AuthUser = Auth::user();
            $ip = $this->getIp();
            $current_time             = Carbon::now();
            $role                    = $AuthUser->role;
            $user                     = $AuthUser;
            $token                     = $user->createToken('msbteWeb')->accessToken;
            $uid                       = $AuthUser->id;

            $rres = Session::create([
                'uid' => $uid,
                'role' => $role,
                'ip' => $ip,
                'starttime' => $current_time,
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ]);

            if (strtoupper($role) == 'ADMIN') {
                return response()->json([
                    'status'         => 'success',
                    'token'         => $token,
                    'data'             => $AuthUser,
                ], 200);
            } else {
                return response()->json([
                    'status'         => 'failure',
                    'message'        => 'Unauthorized User...',
                ], 200);
            }
        } else {

            return response()->json([
                'status'         => 'failure',
                'message'        => 'Invalid Login Credentials',
            ], 400);
        }
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }

    public function logout()
    {
        try {
            $AuthUser = Auth::user();
            $current_time                  = Carbon::now();
            $result                         = Session::select(['endtime', 'uid', 'id'])->where('uid', $AuthUser->id)->orderBy('id', 'DESC')->first()->update(['endtime'     => $current_time]);
            $result = OauthAccessToken::select(['id', 'user_id', 'revoked', 'created_at'])->where('user_id', $AuthUser->id)->orderBy('created_at', 'DESC')->first();
            $result->revoked = '1';
            $result->save();

            return response()->json([
                'status'         => 'success',
                'message'        => 'User Logged out Successfully...',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'         => 'failure',
                'message'        => 'Problem Logging Out...Error:' . $e->getMessage(),
            ], 400);
        }
    }
}
