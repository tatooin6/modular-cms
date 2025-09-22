<?php

namespace App\Services;

use App\Entities\Media;
use App\Interfaces\MediaRepositoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class MediaService
{
    private MediaRepositoryInterface $repository;
    private array $allowedTypes = ['image', 'video', 'audio', 'graph', 'file']; // TODO: check if this can be enums

    public function __construct(MediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createMedia(
        string $type,
        string $title,
        string $description,
        string $sourceUrl,
        array $metadata = []
    ): Media {
        if (!in_array($type, $this->allowedTypes, true)) {
            throw new \InvalidArgumentException("Invalid media type: $type");
        }

        $media = new Media(
            Uuid::uuid4()->toString(),
            $type,
            $title,
            $description,
            $sourceUrl,
            new DateTimeImmutable(),
            $metadata
        );

        $this->repository->save($media);

        return $media;
    }

    public function searchByType(string $type): array
    {
        return $this->repository->findByType($type);
    }

    public function searchByTitle(string $title): array
    {
        return $this->repository->findByTitle($title);
    }
}
