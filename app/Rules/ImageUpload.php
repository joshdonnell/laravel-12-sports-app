<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

final readonly class ImageUpload implements ValidationRule
{
    /**
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rule = File::image()
            ->min('1kb')
            ->max(config('app.uploads.images.maxFilesize'))
            ->dimensions(
                Rule::dimensions()
                    ->maxWidth(config('app.uploads.images.maxWidth'))
                    ->maxHeight(config('app.uploads.images.maxHeight'))
            );

        $validator = Validator::make([$attribute => $value], [$attribute => $rule]);

        if ($validator->fails()) {
            foreach ($validator->errors()->get($attribute) as $message) {
                if (is_array($message)) {
                    foreach ($message as $msg) {
                        $fail((string) $msg);
                    }
                } else {
                    $fail((string) $message);
                }
            }
        }
    }
}
