<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
      * ユーザの登録
      *
      * @param  Request $request
      * @return Response
      */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        $credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);

        $input = $request->all();

        $hashedPassword = Hash::make($input['password']);

        $user = new User;
        $user->username = $input['username'];
        $user->email    = $input['email'];
        $user->password = $hashedPassword;
        $user->save();

        $credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);

        $data = ['id' => $user['id'], 'username' => $user['username'], 'token' => $token];

        return response()->json(['message' => '', 'code' => 200, 'data' => $data]);
    }

    // /**
    //   * ユーザ認証
    //   *
    //   * @param  Request $request
    //   * @return Response
    //   */
    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'email'    => 'required',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only(['email', 'password']);

    //     if(! $token = auth()->attempt($credentials)){
    //         return response()->json(['message' => '$credentials'], 401);
    //     }

    //     return response()->json(['token' => $token], 200);
    // }

    // /**
    //  * ログインユーザの表示
    //  *
    //  * @return Response
    //  */
    // public function me()
    // {
    //     return response()->json(auth()->user(), 200);
    // }
}