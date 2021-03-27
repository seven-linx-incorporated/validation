<?php
declare(strict_types=1);

namespace SevenLinX\Validation\Tests\Stubs;

use SevenLinX\Validation\Contracts\ValidatorContract;

final class ValidatorStub implements ValidatorContract
{
    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'foo' => 'string',
        ];
    }

    /**
     * @inheritDoc
     */
    public function messages(): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function customAttributes(): ?array
    {
        return null;
    }
}