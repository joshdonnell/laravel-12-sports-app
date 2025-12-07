<?php

declare(strict_types=1);

namespace App\Http\Requests\Round;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class CreateRoundRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $user = $this->user();
        assert($user instanceof User);

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'round_number' => [
                'required',
                'integer',
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
