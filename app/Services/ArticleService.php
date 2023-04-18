<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    /**
     * 記事 登録
     * @param string $token
     * @param string $title
     * @param string $text
     * @return \App\Models\Article
     */
    public function createArticle($title, $text)
    {
        $article = new Article();

        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        $article->fill([
            "user_id" => $user_id,
            "title" => $title,
            "text" => $text,
        ]);

        $article->saveOrFail();

        return $article;
    }
}