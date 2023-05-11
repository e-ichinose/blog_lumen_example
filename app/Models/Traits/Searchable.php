<?php

namespace App\Models\Traits;

/**
 * Trait Searchable
 * @package App\Models\Traits
 */
trait Searchable
{
    /**
     * 部分一致
     * @param string $keyword
     * @return string
     */
    protected function escapePartialLike(string $keyword): string
    {
        return '%' . addcslashes($keyword, '%_\\') . '%';
    }

    /**
     * 前方一致
     * @param string $keyword
     * @return string
     */
    protected function escapeFirstLike(string $keyword): string
    {
        return '%' . addcslashes($keyword, '%_\\');
    }

    /**
     * 後方一致
     * @param string $keyword
     * @return string
     */
    protected function escapeBackLike(string $keyword): string
    {
        return addcslashes($keyword, '%_\\') . '%';
    }
}
