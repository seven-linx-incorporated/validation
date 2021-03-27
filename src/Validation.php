<?php
declare(strict_types=1);

namespace SevenLinX\Validation;

use Closure;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SevenLinX\Validation\Contracts\ValidationContract;
use SevenLinX\Validation\Contracts\ValidatorContract;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class Validation implements ValidationContract
{
    public function __construct(
        private Factory $validation,
        private ?Closure $responseBuilder = null,
        private ?Closure $errorFormatter = null
    ) {
    }

    public function setErrorFormatter(Closure $errorFormatter): void
    {
        $this->errorFormatter = $errorFormatter;
    }

    public function setResponseBuilder(Closure $responseBuilder): void
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param  \Illuminate\Http\Request                           $request
     * @param  \SevenLinX\Validation\Contracts\ValidatorContract  $validator
     *
     * @return mixed[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(Request $request, ValidatorContract $validator): array
    {
        $validation = $this->validation->make(
            $request->all(),
            $validator->rules(),
            $validator->messages() ?? [],
            $validator->customAttributes() ?? []
        );

        if ($validation->fails()) {
            throw new ValidationException(
                $validation,
                $this->buildFailedValidationResponse($request, $this->formatValidationErrors($validation))
            );
        }

        return $this->extractFromRules($request, $validator->rules());
    }

    private function buildFailedValidationResponse(
        Request $request,
        array $errors
    ): SymfonyResponse {
        if ($this->responseBuilder !== null) {
            return ($this->responseBuilder)($request, $errors);
        }

        return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function formatValidationErrors(Validator $validator): array
    {
        if ($this->errorFormatter !== null) {
            return ($this->errorFormatter)($validator);
        }

        return $validator->errors()->getMessages();
    }

    private function extractFromRules(Request $request, array $rules): array
    {
        return $request->only($this->collectAllFromRules($rules));
    }

    private function collectAllFromRules(array $rules): array
    {
        return Collection::make($rules)
            ->keys()
            ->map(function ($rule) {
                return Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
            })
            ->unique()
            ->toArray();
    }
}