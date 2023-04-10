<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $input)
    {
        $hashedPassword = Hash::make($input['password']);

        $user = new User;
        $user->username = $input['username'];
        $user->email    = $input['email'];
        $user->password = $hashedPassword;
        $user->save();

        return $user;
    }

    public function generateToken($email, $password)
    {
        $credentials = ['email' => $email, 'password' => $password];
        $token = auth()->attempt($credentials);

        return $token;
    }
}