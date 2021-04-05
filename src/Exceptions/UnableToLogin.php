<?php

namespace CodebarAg\DocuWare\Exceptions;

use RuntimeException;

class UnableToLogin extends RuntimeException
{
    public static function create(): self
    {
        throw new self('Ensure your credentials are correct');
    }
}
