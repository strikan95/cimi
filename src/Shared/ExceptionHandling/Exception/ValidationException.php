<?php

namespace App\Shared\ExceptionHandling\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends HttpException
{
    const MESSAGE = 'There was a validation error.';
    const STATUS_CODE = 400;

    private array $errors;

    public function __construct(
        array $errors,
        \Throwable $previous = null,
        array $headers = [],
    ) {
        $this->errors = $errors;

        parent::__construct(
            self::STATUS_CODE,
            self::MESSAGE,
            $previous,
            $headers,
            1,
        );
    }

    public function getDetails(): array
    {
        return $this->errors;
    }
}
