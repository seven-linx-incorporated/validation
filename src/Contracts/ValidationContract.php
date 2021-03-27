<?php
declare(strict_types=1);

namespace SevenLinX\Validation\Contracts;

use Closure;
use Illuminate\Http\Request;

interface ValidationContract
{
    public function setErrorFormatter(Closure $errorFormatter): void;

    public function setResponseBuilder(Closure $responseBuilder): void;

    /**
     * @param  \Illuminate\Http\Request                           $request
     * @param  \SevenLinX\Validation\Contracts\ValidatorContract  $validator
     *
     * @return mixed[]
     */
    public function validate(Request $request, ValidatorContract $validator): array;
}