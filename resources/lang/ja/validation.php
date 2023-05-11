<?php

return [
    "required" => ":attributeは必須です。",
    "email" => "有効なメールアドレスを入力してください。",
    'string' => ':attributeは文字列にしてください',
    'integer' => ':attributeは整数にしてください',
    "min" => [
        "string" => ":attributeは:min文字以上で入力してください。",
    ],
    "max" => [
        "string" => ":attributeは:max文字以下で入力してください。",
    ],

    "attributes" => [
        "username" => "ユーザ名",
        "email" => "メールアドレス",
        "password" => "パスワード",
        "title" => "タイトル",
        "text" => "本文",
        "article_id" => "記事ID",
        "user_id" => "ユーザーID",
    ],
];
