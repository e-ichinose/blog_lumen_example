<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Exceptions\ArticleException;
use Illuminate\Http\Request;
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
            $this->articleService->createArticle(
                $input["title"],
                $input["text"]
            );

            return $this->success();

          } catch(ArticleException $e) {
            Log::error($e->getMessage());
            return $this->articleError($e->getMessage());
          } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->serverError();
          }
    }

    /**
     * 記事の更新
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $articleId)
    {
        $rules = [
            "title" => ["required"],
            "text" => ["required"],
        ];

        $this->validate($request, $rules, $this->messages);

        $input = $request->all();

        try {
            $this->articleService->updateArticle(
                $input["title"],
                $input["text"],
                $articleId
            );

            return $this->success();
        } catch(ArticleException $e) {
            Log::error($e->getMessage());
            return $this->articleError($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->serverError();
        }
    }
}
