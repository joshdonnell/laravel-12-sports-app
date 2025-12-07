<?php

declare(strict_types=1);

namespace App\Http\Requests\Venue;

use App\Enums\Role;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateVenueRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $venue = $this->venue;
        assert($user instanceof User);
        assert($venue instanceof Venue);

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Venue::class, 'name')->ignore($venue->uuid, 'uuid'),
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'website' => [
                'nullable',
                'string',
                'url',
            ],
        ];

        if ($user->hasRole(Role::SuperAdmin)) {
            $rules['sport_id'] = [
                'required',
                'integer',
                'exists:sports,id',
            ];
        }

        return $rules;
    }
}
