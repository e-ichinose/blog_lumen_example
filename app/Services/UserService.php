<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * ユーザー 登録
     * @param string $username
     * @param string $email
     * @param string $password
     * @return \App\Models\User
     */
    public function createUser($username, $email, $password)
    {
        $hashedPassword = Hash::make($password);

        $user = new User();
        $user->fill([
            "username" => $username,
            "email" => $email,
            "password" => $hashedPassword,
        ]);
        $user->saveOrFail();

        return $user;
    }

    /**
     * 認証トークン発行
     * @param string $email
     * @param string $password
     * @return string
     */
    public function generateToken($email, $password)
    {
        $credentials = ['email' => $email, 'password' => $password];
        $token = auth()->attempt($credentials);

        return $token;
    }
}