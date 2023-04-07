<?php

//ここに色々書いていく

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class  UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // 新しいレコードを作成
    // public function store(Request $request)
    // {

    //     $name = $request->input('name');

    //     // リクエストデータのバリデーション
    //     $this->validate($request, [
    //         // バリデーションルール
    //     ]);

    //     // 新しいレコードを作成
    //     $record = UserModel::create([
    //         //
    //     ]);

    //     return response()->json($record );

    // }
}
