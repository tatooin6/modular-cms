<?php

use App\Repositories\InMemoryMediaRepository;
use App\Services\MediaService;

test('media can be created (without persistency)', function () {
    $repo = new InMemoryMediaRepository();
    $service = new MediaService($repo);

    $media = $service->createMedia(
        "image",
        "Logo",
        "Example logo",
        "http://example.com/logo.png"
    );

    expect($media->title)->toBe("Logo")
        ->and($media->type)->toBe("image")
        ->and($media->description)->toBe("Example logo")
        ->and($media->sourceUrl)->toBe("http://example.com/logo.png");
});

test('invalid media type throws exception', function () {
    $repo = new InMemoryMediaRepository();
    $service = new MediaService($repo);

    $service->createMedia(
        "invalid-type",
        "Broken",
        "This should fail",
        "http://example.com/fail.png"
    );
})->throws(InvalidArgumentException::class, "Invalid media type: invalid-type");

test('media can be searched by type', function () {
    $repo = new InMemoryMediaRepository();
    $service = new MediaService($repo);

    $service->createMedia("image", "Logo", "Logo test", "http://example.com/logo.png");
    $service->createMedia("video", "Trailer", "Video test", "http://example.com/trailer.mp4");

    $images = $service->searchByType("image");

    expect($images)->toHaveCount(1)
        ->and($images[0]->title)->toBe("Logo");
});

test('media can be searched by title', function () {
    $repo = new InMemoryMediaRepository();
    $service = new MediaService($repo);

    $service->createMedia("image", "Company Logo", "Description", "http://example.com/logo.png");
    $service->createMedia("video", "Product Trailer", "Description", "http://example.com/trailer.mp4");

    $results = $service->searchByTitle("logo");

    expect($results)->toHaveCount(1)
        ->and($results[0]->type)->toBe("image");
});
