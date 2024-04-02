<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Exception;

abstract class DomainError extends Exception implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct($this->errorMessage());
    }
}
