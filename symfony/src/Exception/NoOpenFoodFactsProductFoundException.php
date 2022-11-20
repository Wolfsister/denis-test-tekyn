<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class NoOpenFoodFactsProductFoundException extends Exception
{
    public function __construct(string $message = "No Open food fact product was found according your search.", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}