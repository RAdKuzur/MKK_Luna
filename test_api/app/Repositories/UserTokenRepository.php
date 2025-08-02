<?php

namespace App\Repositories;

use App\Models\UserToken;

class UserTokenRepository
{
    public function getByUsername($username){
        return UserToken::where('username', $username)->first();
    }
    public function create($username, $password, $token){
        return UserToken::create([
            'username' => $username,
            'password' => $password,
            'token' => $token
        ]);
    }
}
