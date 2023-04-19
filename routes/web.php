<?php
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// ユーザー新規登録
$router->post("/api/user/register", "UserController@register");

// ユーザー認証
$router->post("/api/user/login", "UserController@login");


$router->group(["middleware" => "token.refresh"], function () use ($router) {
    // 認証が必要なAPIのroute定義

    // 記事投稿
    $router->post("/api/article", "ArticleController@create");
});
