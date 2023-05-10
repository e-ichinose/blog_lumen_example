<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ArticleException;
use App\Models\Traits\Searchable;

class Article extends Model
{
    use HasFactory;
    use Searchable;

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

    /**
     * 記事一覧取得
     * 
     * @param int $perPage
     * @param array $conditions
     * @return mixed
     */
    public function findByQuery($perPage, array $conditions = [])
    {
        $query = self::newQuery();
        if (!empty($conditions['article_id'])) {
            // 完全一致
            $query->where('article_id', $conditions['article_id']);
        }
        if (!empty($conditions['user_id'])) {
            // 完全一致
            $query->where('user_id', $conditions['user_id']);
        }
        if (!empty($conditions['title'])) {
            // 部分一致
            $query->where('title', 'LIKE', $this->escapePartialLike($conditions['title']));
        }
        if (!empty($conditions['text'])) {
            // 部分一致
            $query->where('text', 'LIKE', $this->escapePartialLike($conditions['text']));
        }
        return $query->orderBy('article_id', 'DESC')->paginate($perPage)->toArray();
    }
}
