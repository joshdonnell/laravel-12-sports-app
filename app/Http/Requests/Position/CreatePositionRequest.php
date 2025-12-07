<?php

declare(strict_types=1);

namespace App\Http\Requests\Position;

use App\Enums\Role;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreatePositionRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        assert($user instanceof User);

        $superAdmin = $user->hasRole(Role::SuperAdmin);
        $sport_id = $superAdmin && $this->string('sport_id')->isNotEmpty()
            ? $this->integer('sport_id')
            : $user->sport_id;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Position::class, 'name')
                    ->where('sport_id', $sport_id),
            ],
            'known_as' => [
                'nullable',
                'string',
            ],
        ];

        if ($superAdmin) {
            $rules['sport_id'] = [
                'required',
                'integer',
                'exists:sports,id',
            ];
        }

        return $rules;
    }
}
