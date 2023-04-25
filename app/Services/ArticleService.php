<?php

namespace App\Services;

use App\Http\Json\CommonJson;
use App\Models\Article;
use App\Models\User;
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

    /**
     * 記事 更新
     * @param string $title
     * @param string $text
     * @return boolean
     */
    public function updateArticle($title, $text, $articleId)
    {
        $user = Auth::user();

        if(!User::find($user->id)) {
            return $this->articleError("ユーザーIDが存在しません");
        }

        if(!$article = Article::find($articleId)) {
            return $this->articleError("記事が存在しません");
        }

        if ($article->user_id != $user->id) {
            return $this->articleError("更新の権限がありません");
        }

        $article->fill([
            "title" => $title,
            "text" => $text,
        ]);

        $article->saveOrFail();

        return true;
    }
}
