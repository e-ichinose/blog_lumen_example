<?php

declare(strict_types=1);

namespace App\Http\Json;

trait CommonJson
{
    private function success($message = '', $items = null): array
    {
        return [
            'message' => $message,
            'code'    => 200,
            'data'    => $items
        ];
    }

    private function serverError(): array
    {
        return [
            'message' => 'Internal error',
            'code'    => 500,
            'data'  => null,
        ];
    }

    private function articleError($message = '', $items = null): array
    {
        return [
            'message' => $message,
            'code'    => 400,
            'data'  => $items,
        ];
    }
}