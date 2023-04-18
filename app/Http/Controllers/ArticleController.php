<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Json\CommonJson;

class ArticleController extends Controller
{
    use CommonJson;

    private $articleService;
    private $messages;

    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        $this->articleService = new ArticleService();
        $this->messages = app("translator")->get("validation");
    }

    /**
     * 記事の投稿
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = [
            "title" => ["required"],
            "text" => ["required"],
        ];

        $this->validate($request, $rules, $this->messages);

        $input = $request->all();

        try {
            DB::beginTransaction();

            $article = $this->articleService->createArticle(
                $input["title"],
                $input["text"]
            );

            $data = [
                "user_id" => $article->user_id,
                "title" => $article->title,
                "text" => $article->text
            ];

            DB::commit();

            return response()->json($this->success("投稿しました", $data));

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($this->serverError());
            // throw $e; 要るのか
        }
    }
}
