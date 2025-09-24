<?php

use App\Strategies\DefaultValidationStrategy;
use App\Strategies\RelaxedValidationStrategy;

test('DefaultValidationStrategy accepts only predefined types', function () {
    $strategy = new DefaultValidationStrategy();

    expect($strategy->isValid('image'))->toBeTrue();
    expect($strategy->isValid('video'))->toBeTrue();
    expect($strategy->isValid('audio'))->toBeTrue();
    expect($strategy->isValid('graph'))->toBeTrue();
    expect($strategy->isValid('file'))->toBeTrue();

    // Case insensitive
    expect($strategy->isValid('IMAGE'))->toBeTrue();

    // Invalid type
    expect($strategy->isValid('gif'))->toBeFalse();
    expect($strategy->isValid('document'))->toBeFalse();
});

test('RelaxedValidationStrategy accepts any type', function () {
    $strategy = new RelaxedValidationStrategy();

    expect($strategy->isValid('image'))->toBeTrue();
    expect($strategy->isValid('video'))->toBeTrue();
    expect($strategy->isValid('gif'))->toBeTrue();
    expect($strategy->isValid('something-weird'))->toBeTrue();
});
