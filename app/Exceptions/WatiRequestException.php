<?php

namespace App\Exceptions;

use RuntimeException;

class WatiRequestException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $responseStatus = null,
        public readonly array $responseBody = []
    ) {
        parent::__construct($message);
    }
}
