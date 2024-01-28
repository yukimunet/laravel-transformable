<?php

declare(strict_types=1);

namespace Yukimunet\LaravelTransformable;

trait Transformable
{
    /**
     * Transform this instance.
     *
     * @template TReturn of mixed
     * @template TDefault of mixed
     *
     * @param callable($this): TReturn $callback
     * @param TDefault|callable($this): TDefault|null $default
     * @return ($this is empty ? ($default is null ? null : TDefault) : TReturn)
     */
    public function transform(callable $callback, mixed $default = null)
    {
        return transform($this, $callback, $default);
    }
}
