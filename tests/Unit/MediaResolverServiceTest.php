<?php

use App\Repositories\InMemoryMediaRepository;
use App\Services\MediaService;
use App\Services\MediaResolverService;
use App\Entities\Article;
use Ramsey\Uuid\Uuid;

test('resolver returns media for article uuids', function () {
    $repo = new InMemoryMediaRepository();
    $strategy = new \App\Strategies\DefaultValidationStrategy();
    $mediaService = new MediaService($repo, $strategy);
    $resolver = new MediaResolverService($repo);

    $image = $mediaService->createMedia(
        "image",
        "Logo",
        "Company logo",
        "http://example.com/logo.png"
    );

    $video = $mediaService->createMedia(
        "video",
        "Trailer",
        "Product trailer",
        "http://example.com/trailer.mp4"
    );

    $article = new Article(
        Uuid::uuid4()->toString(),
        "My Article",
        "Article content",
        [$image->uuid],
        [$video->uuid]
    );

    $resolved = $resolver->resolve($article);

    expect($resolved)->toHaveCount(2)
        ->and($resolved[0]->uuid)->toBe($image->uuid)
        ->and($resolved[1]->uuid)->toBe($video->uuid);
});

test('resolver skips non-existing media uuids', function () {
    $repo = new InMemoryMediaRepository();
    $strategy = new \App\Strategies\DefaultValidationStrategy();
    $mediaService = new MediaService($repo, $strategy);
    $resolver = new MediaResolverService($repo);

    $image = $mediaService->createMedia(
        "image",
        "Logo",
        "Company logo",
        "http://example.com/logo.png"
    );

    $article = new Article(
        Uuid::uuid4()->toString(),
        "My Article",
        "Article content",
        [$image->uuid],
        ["fake-uuid"]
    );

    $resolved = $resolver->resolve($article);

    expect($resolved)->toHaveCount(1)
        ->and($resolved[0]->uuid)->toBe($image->uuid);
});

test('resolver returns empty when article has no media', function () {
    $repo = new InMemoryMediaRepository();
    $resolver = new MediaResolverService($repo);

    $article = new Article(
        Uuid::uuid4()->toString(),
        "Empty Article",
        "No media here"
    );

    $resolved = $resolver->resolve($article);

    expect($resolved)->toBeEmpty();
});
