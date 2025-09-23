<?php

use App\Repositories\InMemoryMediaRepository;
use App\Services\MediaService;
use App\Services\MediaMetadataService;

test('metadata can be enriched for existing media', function () {
    $repo = new InMemoryMediaRepository();
    $mediaService = new MediaService($repo);
    $metadataService = new MediaMetadataService($repo);

    $media = $mediaService->createMedia(
        "image",
        "Logo",
        "Company logo",
        "http://example.com/logo.png"
    );

    $updated = $metadataService->enrich($media->uuid, [
        "width" => "1920",
        "height" => "1080",
    ]);

    expect($updated->metadata)
        ->toHaveKey("width", "1920")
        ->toHaveKey("height", "1080");
});

test('metadata enrichment merges with existing metadata', function () {
    $repo = new InMemoryMediaRepository();
    $mediaService = new MediaService($repo);
    $metadataService = new MediaMetadataService($repo);

    $media = $mediaService->createMedia(
        "image",
        "Logo",
        "Company logo",
        "http://example.com/logo.png",
        ["format" => "png"]
    );

    $updated = $metadataService->enrich($media->uuid, [
        "width" => "1920",
    ]);

    expect($updated->metadata)
        ->toHaveKey("format", "png")
        ->toHaveKey("width", "1920");
});

test('enriching metadata for non-existing media returns null', function () {
    $repo = new InMemoryMediaRepository();
    $metadataService = new MediaMetadataService($repo);

    $updated = $metadataService->enrich("fake-uuid", [
        "foo" => "bar",
    ]);

    expect($updated)->toBeNull();
});
