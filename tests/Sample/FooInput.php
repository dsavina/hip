<?php

declare(strict_types=1);

namespace Test\TheCodingMachine\Hip\Sample;

class FooInput
{
    public string $label;
    public ?int $value = null;
    /** @var array<string> */
    public array $tags = [];
    public bool $archived = false;
}
