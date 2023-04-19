<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

            $result = $this->articleService->createArticle(
                $input["title"],
                $input["text"]
            );

            if(!$result) {
                return $this->articleError("記事の投稿に失敗しました");
            }

            DB::commit();

            return $this->success();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->serverError();
        }
    }
}
