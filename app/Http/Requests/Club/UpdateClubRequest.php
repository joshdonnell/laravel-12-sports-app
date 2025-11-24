<?php

declare(strict_types=1);

namespace App\Http\Requests\Clubs;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateClubRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'known_as' => ['nullable', 'string', 'max:255'],
            'official_name' => ['nullable', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
