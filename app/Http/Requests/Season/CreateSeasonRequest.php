<?php

declare(strict_types=1);

namespace App\Http\Requests\Season;

use Illuminate\Foundation\Http\FormRequest;

final class CreateSeasonRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
