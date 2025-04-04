<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success(User::all());
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*$request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);*/

        $user = User::find($id);

        if($user){

            $user->update($request->all());

            return ApiResponse::success($user);

        }

        return ApiResponse::error('Usuário não encontrado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return ApiResponse::error('The method is not implemented');
    }

    /**
     * Find user by email
     */
    public function findUserEmail(Request $request){

        $user = User::where('email', $request->email)->get();

        return $user;
    }

}
