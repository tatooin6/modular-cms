<?php

namespace App\Services;

use App\Entities\Media;
use App\Interfaces\MediaRepositoryInterface;
use App\Interfaces\MediaValidationStrategy;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;

class MediaService
{
    private MediaRepositoryInterface $repository;
    private MediaValidationStrategy $validationStrategy;

    public function __construct(
        MediaRepositoryInterface $repository,
        MediaValidationStrategy $validationStrategy
    ) {
        $this->repository = $repository;
        $this->validationStrategy = $validationStrategy;
    }

    public function createMedia(
        string $type,
        string $title,
        string $description,
        string $sourceUrl,
        array $metadata = []
    ): Media {
        if (!$this->validationStrategy->isValid($type)) {
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
