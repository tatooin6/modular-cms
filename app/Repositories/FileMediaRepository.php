<?php

namespace App\Repositories;

use App\Entities\Media;
use App\Interfaces\MediaRepositoryInterface;
use DateTimeImmutable;

class FileMediaRepository implements MediaRepositoryInterface
{
    private string $filePath;

    public function __construct(string $filePath = 'storage/media.json')
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    private function load(): array
    {
        $data = json_decode(file_get_contents($this->filePath), true);
        return $data ?? [];
    }

    private function saveAll(array $data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function save(Media $media): void
    {
        $all = $this->load();
        $all[$media->uuid] = [
            'uuid' => $media->uuid,
            'type' => $media->type,
            'title' => $media->title,
            'description' => $media->description,
            'sourceUrl' => $media->sourceUrl,
            'uploadedAt' => $media->uploadedAt->format(DateTimeImmutable::ATOM),
            'metadata' => $media->metadata,
        ];
        $this->saveAll($all);
    }

    public function findByUuid(string $uuid): ?Media
    {
        $all = $this->load();
        if (!isset($all[$uuid])) {
            return null;
        }

        $item = $all[$uuid];
        return new Media(
            $item['uuid'],
            $item['type'],
            $item['title'],
            $item['description'],
            $item['sourceUrl'],
            new DateTimeImmutable($item['uploadedAt']),
            $item['metadata']
        );
    }

    public function findByType(string $type): array
    {
        return array_values(array_filter(
            array_map(fn($item) => new Media(
                $item['uuid'],
                $item['type'],
                $item['title'],
                $item['description'],
                $item['sourceUrl'],
                new DateTimeImmutable($item['uploadedAt']),
                $item['metadata']
            ), $this->load()),
            fn(Media $m) => $m->type === $type
        ));
    }

    public function findByTitle(string $title): array
    {
        return array_values(array_filter(
            array_map(fn($item) => new Media(
                $item['uuid'],
                $item['type'],
                $item['title'],
                $item['description'],
                $item['sourceUrl'],
                new DateTimeImmutable($item['uploadedAt']),
                $item['metadata']
            ), $this->load()),
            fn(Media $m) => stripos($m->title, $title) !== false
        ));
    }
}
