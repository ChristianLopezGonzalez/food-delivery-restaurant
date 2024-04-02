<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain\Errors;

use App\Shared\Domain\DomainError;

final class RequiredConceptsNotFound extends DomainError
{
    public function __construct(private array $requiredConcepts)
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        $lastWord = array_pop($this->requiredConcepts);
        $words = implode(', ', $this->requiredConcepts);
        return sprintf('Selected food must be %s or %s.', $words, $lastWord);
    }
}
