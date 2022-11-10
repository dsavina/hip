<?php

declare(strict_types=1);

namespace TheCodingMachine\Hip;

use Symfony\Component\HttpFoundation\Request;

interface Processor
{
    /**
     * Process an HTTP request to validate model and instantiate given class
     *
     * @param class-string<T> $className
     *
     * @return T
     *
     * @template T of object
     */
    public function process(Request $request, string $className): object;
}
