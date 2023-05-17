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

$router->group(['middleware' => 'cors'], function ($router) {

  // ユーザー新規登録
  $router->post("/api/user/register", "UserController@register");

  // ユーザー認証
  $router->post("/api/user/login", "UserController@login");

  $router->group(["middleware" => "token.refresh"], function () use ($router) {
      // 認証が必要なAPIのroute定義

      // 記事投稿
      $router->post("/api/article", "ArticleController@create");

      // 記事更新
      $router->post("/api/article/update/{articleId}", "ArticleController@update");

      // 記事削除
      $router->delete("/api/article/delete/{articleId}", "ArticleController@delete");

      // 記事一覧取得
      $router->get("/api/article", "ArticleController@index");
  });
});