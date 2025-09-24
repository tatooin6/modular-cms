<?php

namespace App\Interfaces;

interface MediaValidationStrategy
{
    public function isValid(string $type): bool;
}
