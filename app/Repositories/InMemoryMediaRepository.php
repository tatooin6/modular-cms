<?php

namespace App\Repositories;

use App\Entities\Media;
use App\Interfaces\MediaRepositoryInterface;

/**
 * Deprecated
 */
class InMemoryMediaRepository implements MediaRepositoryInterface
{
    private array $storage = [];

    public function save(Media $media): void
    {
        $this->storage[$media->uuid] = $media;
    }

    public function findByUuid(string $uuid): ?Media
    {
        return $this->storage[$uuid] ?? null;
    }

    public function findByType(string $type): array
    {
        return array_values(
            array_filter($this->storage, fn(Media $m) => $m->type === $type)
        );
    }

    public function findByTitle(string $title): array
    {
        return array_values(
            array_filter($this->storage, fn(Media $m) => stripos($m->title, $title) !== false)
        );
    }
}
