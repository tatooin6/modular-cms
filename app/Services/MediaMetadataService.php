<?php

namespace App\Services;

use App\Entities\Media;
use App\Interfaces\MediaRepositoryInterface;

class MediaMetadataService
{
    private MediaRepositoryInterface $repository;

    public function __construct(MediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function enrich(string $uuid, array $newMetadata): ?Media
    {
        $media = $this->repository->findByUuid($uuid);

        if (!$media) {
            return null;
        }

        $media->metadata = array_merge($media->metadata, $newMetadata);
        $this->repository->save($media);

        return $media;
    }
}
