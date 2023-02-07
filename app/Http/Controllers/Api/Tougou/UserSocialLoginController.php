<?php

namespace App\Http\Controllers\Api\Tougou;

use App\Http\Controllers\Controller;
use App\Models\UserSocialLogin;
use App\Http\Requests\StoreUserSocialLoginRequest;
use App\Http\Requests\UpdateUserSocialLoginRequest;

class UserSocialLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreUserSocialLoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserSocialLoginRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserSocialLogin  $userSocialLogin
     * @return \Illuminate\Http\Response
     */
    public function show(UserSocialLogin $userSocialLogin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserSocialLogin  $userSocialLogin
     * @return \Illuminate\Http\Response
     */
    public function edit(UserSocialLogin $userSocialLogin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserSocialLoginRequest  $request
     * @param  \App\Models\UserSocialLogin  $userSocialLogin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserSocialLoginRequest $request, UserSocialLogin $userSocialLogin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserSocialLogin  $userSocialLogin
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSocialLogin $userSocialLogin)
    {
        //
    }
}
