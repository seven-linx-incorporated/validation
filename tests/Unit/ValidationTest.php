<?php
declare(strict_types=1);

namespace SevenLinX\Validation\Tests\Unit;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use SevenLinX\Validation\Tests\AbstractTestCase;
use SevenLinX\Validation\Tests\Stubs\ValidatorStub;
use SevenLinX\Validation\Validation;

/**
 * @covers \SevenLinX\Validation\Validation
 */
final class ValidationTest extends AbstractTestCase
{
    public function testValidate(): void
    {
        $request = new Request();
        $request->attributes->set('foo', 'bar');

        $validatorStub = new ValidatorStub();

        $validator = $this->mock(Validator::class);
        $validator->shouldReceive('fails')
            ->andReturnFalse();

        $validationFactory = $this->mock(Factory::class);
        $validationFactory->shouldReceive('make')
            ->with(
                $request->all(),
                $validatorStub->rules(),
                $validatorStub->messages(),
                $validatorStub->customAttributes()
            )
            ->andReturn($validator);

        $validation = new Validation($validationFactory);
        $validation->validate($request, $validatorStub);
    }

    public function testIfValidationFails(): void
    {
        $request = new Request();
        $request->attributes->set('foo', 'bar');

        $validatorStub = new ValidatorStub();

        $validator = $this->mock(Validator::class);
        $validator->shouldReceive('fails')
            ->once()
            ->andReturnTrue();
        $validator->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag(['foo' => 'bar']));

        $validationFactory = $this->mock(Factory::class);
        $validationFactory->shouldReceive('make')
            ->once()
            ->with(
                $request->all(),
                $validatorStub->rules(),
                $validatorStub->messages(),
                $validatorStub->customAttributes()
            )
            ->andReturn($validator);

        $validation = new Validation($validationFactory);
        $this->expectException(ValidationException::class);
        $validation->validate($request, $validatorStub);
    }
}