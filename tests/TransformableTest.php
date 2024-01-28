<?php

declare(strict_types=1);

namespace Yukimunet\LaravelTransformable\Tests;

use Countable;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Yukimunet\LaravelTransformable\Transformable;

/**
 * @internal
 *
 * @covers \Yukimunet\LaravelTransformable\Transformable
 */
class TransformableTest extends TestCase
{
    public function testTransformableClassWithCallback(): void
    {
        $name = TransformableClass::make('MyName')->transform(function ($transformable): string {
            return Str::of($transformable->getName())->snake()->toString();
        });

        $this->assertSame('my_name', $name);
    }

    public function testTransformableClassWithInvokableClass(): void
    {
        $name = TransformableClass::make('MyName')->transform(new class () {
            public function __invoke(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        });

        $this->assertSame('my_name', $name);
    }

    public function testTransformableClassWithNoneInvokableClass(): void
    {
        $this->expectException('Error');

        $name = TransformableClass::make('MyName')->transform(new class () {
            public function getSnakeName(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        });

        $this->assertSame('my_name', $name);
    }

    public function testTransformableClassWithCallbackWhenEmpty(): void
    {
        $name = TransformableClass::make('')->transform(function ($transformable): string {
            return Str::of($transformable->getName())->snake()->toString();
        }, 'OtherName');

        $this->assertSame('OtherName', $name);
    }

    public function testTransformableClassWithInvokableClassWhenEmpty(): void
    {
        $name = TransformableClass::make('')->transform(new class () {
            public function __invoke(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        }, 'OtherName');

        $this->assertSame('OtherName', $name);
    }

    public function testTransformableClassWithNoneInvokableClassWhenEmpty(): void
    {
        $this->expectException('Error');

        $name = TransformableClass::make('')->transform(new class () {
            public function getSnakeName(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        }, 'OtherName');

        $this->assertSame('OtherName', $name);
    }

    public function testTransformableClassWithCallbackAndDefaultCallbackWhenEmpty(): void
    {
        $name = TransformableClass::make('')->transform(function ($transformable): string {
            return Str::of($transformable->getName())->snake()->toString();
        }, function () {
            return 'OtherName';
        });

        $this->assertSame('OtherName', $name);
    }

    public function testTransformableClassWithInvokableClassAndDefaultCallbackWhenEmpty(): void
    {
        $name = TransformableClass::make('')->transform(new class () {
            public function __invoke(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        }, function ($transformable): string {
            return 'OtherName';
        });

        $this->assertSame('OtherName', $name);
    }

    public function testTransformableClassWithNoneInvokableClassAndDefaultCallbackWhenEmpty(): void
    {
        $this->expectException('Error');

        $name = TransformableClass::make('')->transform(new class () {
            public function getSnakeName(TransformableClass $transformable): string
            {
                return Str::of($transformable->getName())->snake()->toString();
            }
        }, function ($transformable) {
            return 'OtherName';
        });

        $this->assertSame('OtherName', $name);
    }
}

class TransformableClass implements Countable
{
    use Transformable;

    private string $name;

    public static function make(string $name): TransformableClass
    {
        $self = new TransformableClass();
        $self->setName($name);
        return $self;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function count(): int
    {
        return strlen($this->name);
    }
}
