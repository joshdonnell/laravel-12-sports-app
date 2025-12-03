<?php

declare(strict_types=1);

namespace App\Http\Requests\Sport;

use Illuminate\Foundation\Http\FormRequest;

final class CreateSportRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:sports,name'],
        ];
    }
}
