<?php

declare(strict_types=1);

use App\Rules\ImageUpload;
use Illuminate\Http\UploadedFile;

it('passes with valid image', function (UploadedFile $file): void {
    $rule = new ImageUpload;
    $failed = false;

    $rule->validate('file', $file, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
})->with([
    'jpg image' => fn () => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(50),
    'png image' => fn () => UploadedFile::fake()->image('photo.png', 100, 100)->size(50),
    'gif image' => fn () => UploadedFile::fake()->image('photo.gif', 100, 100)->size(50),
    'webp image' => fn () => UploadedFile::fake()->image('photo.webp', 100, 100)->size(50),
    'max dimensions' => fn () => UploadedFile::fake()->image('photo.jpg', 5000, 5000)->size(50),
    'min size' => fn () => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(1),
]);

it('fails with invalid image', function (UploadedFile $file): void {
    $rule = new ImageUpload;
    $failed = false;

    $rule->validate('file', $file, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
})->with([
    'pdf file' => fn () => UploadedFile::fake()->create('document.pdf', 50),
    'txt file' => fn () => UploadedFile::fake()->create('document.txt', 50),
    'exceeds max width' => fn () => UploadedFile::fake()->image('photo.jpg', 5001, 100)->size(50),
    'exceeds max height' => fn () => UploadedFile::fake()->image('photo.jpg', 100, 5001)->size(50),
    'below min size' => fn () => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(0),
    'exceeds max file size' => fn () => UploadedFile::fake()->image('photo.jpg', 100, 100)->size(11 * 1024),
]);
