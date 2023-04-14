<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use Illuminate\Http\Response;

/**
 * トークンリフレッシュミドルウェア
 * APIリクエストに含まれるトークンの検証および更新を行うミドルウェアクラス
 */
class TokenRefreshMiddleware
{
    protected $userService;

    /**
     * TokenRefreshMiddlewareのコンストラクタ
     *
     * @param UserService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * トークンの検証および更新を行うハンドラ
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response レスポンスインスタンス
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json(
                [
                    "message" => "トークンが存在しません",
                    "code" => Response::HTTP_UNAUTHORIZED,
                    "data" => null,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $result = $this->userService->checkToken($token);

        if ($result["code"] !== Response::HTTP_OK) {
            return response()->json($result, $result["code"]);
        }

        $response = $next($request);

        if (!empty($result["data"]["token"])) {
            $response->header("Authorization", $result["data"]["token"]);
        }

        return $response;
    }
}
