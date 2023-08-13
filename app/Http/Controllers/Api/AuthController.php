<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;;

use App\Services\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function login(Request $request)
    {

        try {

            if ($request->query()) {

                return response()->json(['message' => 'Query parameters are not allowed']);
            } else {
                $user = User::where('email', $request->email)->first();
                // if ($user->code != null) {
                //     return response()->json(['message' => 'You Should Verfiy Your Code..!']);
                // }

                $rules = [
                    "email" => "required",
                    "password" => "required"
                ];
                $validator = Validator::make($request->only(['email', 'password']), $rules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                $credentials = $request->only(['email', 'password']);

                $token = Auth::guard('api')->attempt($credentials);


                if (!$token)
                    return response()->json(['error' => 'Unauthorized'], 401);

                $user = Auth::guard('api')->user();


                return response()->json(['token' => $token, 'user' => $user]);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], $ex->getCode());
        }
    }


    public function verify_code(Request $request)
    {
        $user = User::where('code', $request->code)->first();
        if ($request->code == $user->code) {
            $user->reset_code();
            return response()->json(['message' => 'Success Verfiy Code..!!']);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
