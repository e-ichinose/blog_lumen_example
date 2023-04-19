<?php

namespace App\Services;

use App\Http\Json\CommonJson;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    use CommonJson;

    /**
     * 記事 登録
     * @param string $title
     * @param string $text
     * @return boolean
     */
    public function createArticle($title, $text)
    {
        $article = new Article();

        $user = Auth::user();

        if(empty($user)) {
            return false;
        }

        $article->fill([
            "user_id" => $user->id,
            "title" => $title,
            "text" => $text,
        ]);

        $article->saveOrFail();

        return true;
    }
}