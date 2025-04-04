<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
*  @OA\GET(
*      path="/api/users",
*      summary="Get all users",
*      description="Get all users",
*      tags={"Users"},
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
    public function index()
    {
        return ApiResponse::success(User::all());
    }

    /**
*  @OA\POST(
*      path="/api/users",
*      summary="Create a user",
*      description="Create a user",
*      tags={"Users"},
*      @OA\Parameter(
*         name="name",
*         in="query",
*         description="name",
*         required=true,
*      ),
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
*     @OA\Parameter(
*         name="active",
*         in="query",
*         description="Active",
*         required=false,
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create($request->all());

        return ApiResponse::success($user);
    }

    /**
*  @OA\GET(
*      path="/api/users/{id}",
*      summary="Get user by id",
*      description="Get user by id",
*      tags={"Users"},
*      @OA\Parameter(
*         name="id",
*         in="path",
*         description="id",
*         required=true,
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function show(string $id)
    {
        $user = User::find($id);

        if($user){
            return ApiResponse::success($user);
        }

        return ApiResponse::error('Usuário não encontrado');
    }

    /**
*  @OA\PUT(
*      path="/api/users/{user}",
*      summary="Update a user",
*      description="Update a user",
*      tags={"Users"},
*      @OA\Parameter(
*         name="name",
*         in="query",
*         description="name",
*         required=false,
*      ),
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
*     @OA\Parameter(
*         name="active",
*         in="query",
*         description="Active",
*         required=false,
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email' .$id,
            'password' => 'required'
        ]);

        $user = User::find($id);

        if($user){

            $user->update($request->all());

            return ApiResponse::success($user);

        }

        return ApiResponse::error('Usuário não encontrado');
    }

    /**
*  @OA\POST(
*      path="/api/findUserEmail",
*      summary="Get user by email",
*      description="Get user by email",
*      tags={"Users"},
*      @OA\Parameter(
*         name="email",
*         in="query",
*         description="email",
*         required=true,
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function findUserEmail(Request $request){

        $user = User::where('email', $request->email)->get();

        if(!$user->isEmpty()){
            return ApiResponse::success($user);
        }

        return ApiResponse::error('Usuário não encontrado');
    }

}
