<?php

declare(strict_types=1);

namespace App\Http\Requests\Club;

use App\Models\User;
use App\Rules\ImageUpload;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateClubRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        assert($user instanceof User);

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'known_as' => [
                'nullable', 'string',
                'max:255',
            ],
            'official_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'code' => [
                'nullable',
                'string',
                'max:10',
            ],
            'logo' => [
                'nullable',
                new ImageUpload,
            ],
            'bio' => [
                'nullable',
                'string',
            ],
            'remove_logo' => [
                'required',
                'bool',
            ],
        ];
    }
}
