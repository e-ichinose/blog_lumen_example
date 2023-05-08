<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ArticleException;

class Article extends Model
{
    use HasFactory;

    protected $primaryKey = "article_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ["user_id", "title", "text"];

    /**
     * 特定記事の取得
     * @param $agencyId
     * @return Article
     * @throws ArticleException
     */
    public function findById($articleId): Article
    {
        $article = self::find($articleId);
        if (is_null($article)) {
            throw new ArticleException('記事が存在しません');
        }
        return $article;
    }
}
