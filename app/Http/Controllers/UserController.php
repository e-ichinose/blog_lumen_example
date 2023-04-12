<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userService;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * ユーザの登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        $input = $request->all();

        try {
            DB::beginTransaction();

            $user = $this->userService->createUser(
                $input["username"],
                $input["email"],
                $input["password"]
            );

            $token = $this->userService->generateToken(
                $input["email"],
                $input["password"]
            );

            $data = [
                "id" => $user->id,
                "username" => $user->username,
                "token" => $token,
            ];

            DB::commit();

            return response()->json([
                "message" => "",
                "code" => 200,
                "data" => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * ユーザの認証
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
        ],[
            'email.required'    => 'メールアドレスは必須です',
            'email.email'       => '有効なメールアドレスを入力してください',
            'password.required' => 'パスワードは必須です',
            'password.min'      => 'パスワードは6文字以上で入力してください',
        ]);

        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            // 認証成功
            $user = auth()->user();

            $token = $this->userService->generateToken(
                $credentials["email"],
                $credentials["password"]
            );

            $data = [
                "id" => $user->id,
                "username" => $user->username,
                "token" => $token,
            ];

            return response()->json([
                "message" => "",
                "code" => 200,
                "data" => $data,
            ]);
        } else {
            // 認証失敗
            return response()->json([
                "message" => "ユーザー認証に失敗しました",
                "code" => 401,
                "data" => null,
            ]);
        }
    }
}