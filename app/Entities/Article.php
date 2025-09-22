<?php

namespace App\Entities;

class Article
{
    public string $articleUuid;
    public string $headline;
    public string $content;
    public array $imageUuids;
    public array $mediaAttachments;

    public function __construct(
        string $articleUuid,
        string $headline,
        string $content,
        array $imageUuids = [],
        array $mediaAttachments = []
    ) {
        $this->articleUuid = $articleUuid;
        $this->headline = $headline;
        $this->content = $content;
        $this->imageUuids = $imageUuids;
        $this->mediaAttachments = $mediaAttachments;
    }
}
