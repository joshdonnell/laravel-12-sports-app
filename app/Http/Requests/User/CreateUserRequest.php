<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\Role;
use App\Models\User;
use App\Rules\ValidEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class CreateUserRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        assert($user instanceof User);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'max:255',
                'email',
                new ValidEmail,
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];

        if ($user->hasRole(Role::SuperAdmin)) {
            $rules['sport_id'] = [
                'required',
                'integer',
                'exists:sports,id',
            ];

            $rules['role'] = [
                'required',
                Rule::enum(Role::class),
            ];
        }

        if ($user->hasRole(Role::Admin)) {
            $rules['role'] = [
                'required',
                Rule::enum(Role::class)->except(Role::SuperAdmin),
            ];
        }

        return $rules;
    }
}
