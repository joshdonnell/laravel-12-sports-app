<?php

declare(strict_types=1);

namespace App\Http\Requests\Sport;

use App\Models\Sport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateSportRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentSport = $this->sport;
        assert($currentSport instanceof Sport);

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Sport::class, 'name')->ignore($currentSport->uuid, 'uuid'),
            ],
        ];
    }
}
