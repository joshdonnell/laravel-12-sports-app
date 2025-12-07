<?php

declare(strict_types=1);

namespace App\Http\Requests\Client;

use App\Enums\Role;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateClientRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $client = $this->client;
        assert($user instanceof User);
        assert($client instanceof Client);

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Client::class, 'name')->ignore($client->uuid, 'uuid'),
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
