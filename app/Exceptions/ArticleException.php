<?php

namespace App\Exceptions;

use Exception;

class ArticleException extends Exception
{
    /**
     * ArticleException constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}