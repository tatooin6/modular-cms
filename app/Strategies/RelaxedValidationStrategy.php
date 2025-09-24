<?php

namespace App\Strategies;

use App\Interfaces\MediaValidationStrategy;

class RelaxedValidationStrategy implements MediaValidationStrategy
{
    public function isValid(string $type): bool
    {
        // always valid, accepts any type
        return true;
    }
}
