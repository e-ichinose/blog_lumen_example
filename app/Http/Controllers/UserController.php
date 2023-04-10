<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}