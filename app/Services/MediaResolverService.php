<?php

namespace App\Services;

use App\Entities\Article;
use App\Interfaces\MediaRepositoryInterface;

class MediaResolverService
{
    private MediaRepositoryInterface $repository;

    public function __construct(MediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function resolve(Article $article): array
    {
        $resolved = [];

        foreach ($article->imageUuids as $uuid) {
            $media = $this->repository->findByUuid($uuid);
            if ($media) {
                $resolved[] = $media;
            }
        }

        foreach ($article->mediaAttachments as $uuid) {
            $media = $this->repository->findByUuid($uuid);
            if ($media) {
                $resolved[] = $media;
            }
        }

        return $resolved;
    }
}
