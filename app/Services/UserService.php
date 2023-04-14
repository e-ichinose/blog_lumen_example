<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class UserService
{
    /**
     * 無効なトークンのエラーレスポンスを返却
     * @return array
     */
    private function invalidTokenResponse()
    {
        return [
            "message" => "無効なトークン",
            "code" => Response::HTTP_UNAUTHORIZED,
            "data" => null,
        ];
    }

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
        $credentials = ["email" => $email, "password" => $password];
        $token = auth()->attempt($credentials);

        return $token;
    }

    /**
     * トークン検証
     * @param string $token
     * @return array
     */
    public function checkToken($token)
    {
        try {
            $payload = JWTAuth::setToken($token)->getPayload();

            if (!$payload) {
                return $this->invalidTokenResponse();
            }

            $exp = $payload->get("exp");

            // 有効期限が切れている場合、トークンを更新
            if ($exp < time()) {
                try {
                    $newToken = JWTAuth::refresh($token);

                    return [
                        "message" => "",
                        "code" => Response::HTTP_OK,
                        "data" => ["token" => $newToken],
                    ];
                } catch (JWTException $e) {
                    return [
                        "message" => "トークンの有効期限が切れています",
                        "code" => Response::HTTP_UNAUTHORIZED,
                        "data" => null,
                    ];
                }
            }

            return [
                "message" => "",
                "code" => Response::HTTP_OK,
                "data" => null,
            ];
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return $this->invalidTokenResponse();
        }
    }
}
