<?php
declare(strict_types=1);

namespace SevenLinX\Validation\Contracts;

interface ValidatorContract
{
    /**
     * @return mixed[]
     */
    public function rules(): array;

    /**
     * @return null|mixed[]
     */
    public function messages(): ?array;

    /**
     * @return null|mixed[]
     */
    public function customAttributes(): ?array;
}