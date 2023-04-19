<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
