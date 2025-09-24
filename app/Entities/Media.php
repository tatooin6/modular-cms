<?php

namespace App\Entities;

use DateTimeImmutable;

class Media
{
    public string $uuid;
    public string $type;
    public string $title;
    public string $description;
    public string $sourceUrl;
    public DateTimeImmutable $uploadedAt;
    public array $metadata;

    public function __construct(
        string $uuid,
        string $type,
        string $title,
        string $description,
        string $sourceUrl,
        DateTimeImmutable $uploadedAt,
        array $metadata = []
    ) {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->sourceUrl = $sourceUrl;
        $this->uploadedAt = $uploadedAt;
        $this->metadata = $metadata;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'sourceUrl' => $this->sourceUrl,
            'uploadedAt' => $this->uploadedAt->format(DATE_ATOM),
            'metadata' => $this->metadata,
        ];
    }
}
