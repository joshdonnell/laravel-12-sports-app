<?php

declare(strict_types=1);

use App\Data\Shared\SelectData;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all fields including icon', function (): void {
    $selectData = SelectData::from([
        'value' => 'option-1',
        'label' => 'Option 1',
        'icon' => 'check',
    ]);

    expect($selectData)
        ->toBeInstanceOf(SelectData::class)
        ->and($selectData->value)->toBe('option-1')
        ->and($selectData->label)->toBe('Option 1')
        ->and($selectData->icon)->toBe('check');
});

it('can be created without icon', function (): void {
    $selectData = SelectData::from([
        'value' => 'option-2',
        'label' => 'Option 2',
        'icon' => null,
    ]);

    expect($selectData)
        ->toBeInstanceOf(SelectData::class)
        ->and($selectData->value)->toBe('option-2')
        ->and($selectData->label)->toBe('Option 2')
        ->and($selectData->icon)->toBeNull();
});

it('errors when value is not provided', function (): void {
    expect(fn (): SelectData => SelectData::from([
        'label' => 'Option 1',
        'icon' => 'check',
    ]))->toThrow(CannotCreateData::class);
});

it('errors when label is not provided', function (): void {
    expect(fn (): SelectData => SelectData::from([
        'value' => 'option-1',
        'icon' => 'check',
    ]))->toThrow(CannotCreateData::class);
});

it('can pick data from array of objects with icon field', function (): void {
    $data = [
        ['id' => 1, 'name' => 'First', 'icon' => 'star'],
        ['id' => 2, 'name' => 'Second', 'icon' => 'heart'],
        ['id' => 3, 'name' => 'Third', 'icon' => 'check'],
    ];

    $result = SelectData::pick('id', 'name', 'icon', $data);

    expect($result)
        ->toBeArray()
        ->toHaveCount(3)
        ->and($result[0])->toBeInstanceOf(SelectData::class)
        ->and($result[0]->value)->toBe('1')
        ->and($result[0]->label)->toBe('First')
        ->and($result[0]->icon)->toBe('star')
        ->and($result[1]->value)->toBe('2')
        ->and($result[1]->label)->toBe('Second')
        ->and($result[1]->icon)->toBe('heart')
        ->and($result[2]->value)->toBe('3')
        ->and($result[2]->label)->toBe('Third')
        ->and($result[2]->icon)->toBe('check');
});

it('can pick data from array of objects without icon field', function (): void {
    $data = [
        ['uuid' => '123e4567-e89b-12d3-a456-426614174000', 'title' => 'Title One'],
        ['uuid' => '223e4567-e89b-12d3-a456-426614174000', 'title' => 'Title Two'],
    ];

    $result = SelectData::pick('uuid', 'title', null, $data);

    expect($result)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($result[0])->toBeInstanceOf(SelectData::class)
        ->and($result[0]->value)->toBe('123e4567-e89b-12d3-a456-426614174000')
        ->and($result[0]->label)->toBe('Title One')
        ->and($result[0]->icon)->toBeNull()
        ->and($result[1]->value)->toBe('223e4567-e89b-12d3-a456-426614174000')
        ->and($result[1]->label)->toBe('Title Two')
        ->and($result[1]->icon)->toBeNull();
});

it('can pick data from empty array', function (): void {
    $result = SelectData::pick('id', 'name', null, []);

    expect($result)
        ->toBeArray()
        ->toBeEmpty();
});

it('converts numeric values to strings when picking', function (): void {
    $data = [
        ['id' => 42, 'name' => 'Answer'],
    ];

    $result = SelectData::pick('id', 'name', null, $data);

    expect($result[0]->value)
        ->toBeString()
        ->toBe('42');
});
