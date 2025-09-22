<?php

namespace App\Interfaces;
use App\Entities\Media;

// Dependency Inversion
// Services don't know the specific implementation (in memory, DB) only the interface.
interface MediaRepositoryInterface
{
    public function save(Media $media): void;
    public function findByUuid(string $uuid): ?Media;
    public function findByType(string $type): array;
    public function findByTitle(string $title): array;
}
