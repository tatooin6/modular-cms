<?php

namespace App\Strategies;

use App\Interfaces\MediaValidationStrategy;

class DefaultValidationStrategy implements MediaValidationStrategy
{
    private array $allowedTypes = ['image', 'video', 'audio', 'graph', 'file'];

    public function isValid(string $type): bool
    {
        return in_array(strtolower($type), $this->allowedTypes, true);
    }
}
