<?php

namespace App\Http\Controllers;

use App\Http\Json\CommonJson;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use CommonJson;

    private $userService;
    private $messages;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userService = new UserService();
        $this->messages = app("translator")->get("validation");
    }

    /**
     * ユーザーの登録
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

            return $this->success("", $data);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * ユーザーのログイン
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            "email" => ["required", "email", "max:255"],
            "password" => ["required", "min:6", "max:255"],
        ];

        $this->validate($request, $rules, $this->messages);

        try {
            $token = $this->userService->generateToken(
                $request["email"],
                $request["password"]
            );

            if (!$token) {
                return response()->json([
                    "message" => "ユーザー認証に失敗しました",
                    "code" => 401,
                    "data" => null,
                ]);
            }

            $user = auth()->user();

            $data = [
                "id" => $user->id,
                "username" => $user->username,
                "token" => $token,
            ];

            return $this->success("", $data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->serverError();
        }
    }

    /**
     * ユーザーの認証
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return auth()->user();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->serverError();
        }
    }
}