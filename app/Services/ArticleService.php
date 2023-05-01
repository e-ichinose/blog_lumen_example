<?php

namespace App\Services;

use App\Http\Json\CommonJson;
use App\Models\Article;
use App\Exceptions\ArticleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ArticleService
{
    use CommonJson;

    /**
     * 記事 登録
     * @param string $title
     * @param string $text
     * @throws ArticleException
     */
    public function createArticle($title, $text)
    {
        DB::transaction(function () use ($title, $text) {
            $article = new Article();

            $user = Auth::user();

            if(empty($user)) {
              throw new ArticleException('ユーザーが存在しません');
            }

            $article->fill([
                "user_id" => $user->id,
                "title" => $title,
                "text" => $text,
            ]);

            $article->saveOrFail();
        });
    }

    /**
     * 記事 更新
     * @param string $title
     * @param string $text
     * @param int $articleId
     * @throws ArticleException
     */
    public function updateArticle($title, $text, $articleId)
    {
        DB::transaction(function () use ($title, $text, $articleId) {
            $user = Auth::user();

            if(empty($user)) {
              throw new ArticleException('ユーザーが存在しません');
            }

            $articleModel = new Article();

            $article = $articleModel->findById($articleId);

            if ($article->user_id != $user->id) {
              throw new ArticleException('更新の権限がありません');
            }

            $article->update([
                "title" => $title,
                "text" => $text,
            ]);
        });
    }
}