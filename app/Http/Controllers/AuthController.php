<?php

namespace App\Http\Controllers;

use App\Services\ApiResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
/**
*  @OA\POST(
*      path="/api/login",
*      summary="Login",
*      description="Login",
*      tags={"Login/Logout"},
*     @OA\Parameter(
*         name="email",
*         in="query",
*         description="email",
*         required=true,
*      ),
*     @OA\Parameter(
*         name="password",
*         in="query",
*         description="Password",
*         required=true,
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function login (Request $request)
    {
       $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $attempt = auth()->attempt(
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if(!$attempt){
            return ApiResponse::unathourized();
        }

        $user = auth()->user();

        $token = $user->createToken($user->name)->plainTextToken;

        return ApiResponse::success(
            [
                'user' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
            );
    }

  /**
*  @OA\DELETE(
*      path="/api/logout",
*      summary="Revoke all users tokens",
*      description="Revoke all users tokens",
*      tags={"Login/Logout"},
*      security={{"bearerAuth":{}}},
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=401,
*          description="Unauthenticated",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Deslogado com sucesso');
    }

    
}
