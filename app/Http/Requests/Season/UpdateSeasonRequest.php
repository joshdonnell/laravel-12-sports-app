<?php

declare(strict_types=1);

namespace App\Http\Requests\Season;

use App\Models\Season;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateSeasonRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentSeason = $this->season;
        assert($currentSeason instanceof Season);

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Season::class, 'name')->ignore($currentSeason->uuid, 'uuid'),
            ],
        ];
    }
}
