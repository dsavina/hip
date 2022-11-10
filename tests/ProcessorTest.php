<?php

declare(strict_types=1);

namespace Test\TheCodingMachine\Hip;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Test\TheCodingMachine\Hip\Sample\FooInput;
use TheCodingMachine\Hip\Processor;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{
    private Processor $processor;

    protected function setUp(): void
    {
        // $this->processor = ...
    }

    public function testProcessExhaustiveInput()
    {
        $request = $this->createRequest(
            <<<JSON
            {
                "label": "It's a me, the foo",
                "value": 42,
                "tags": ["the-foo", "not-a-bar"],
                "archived": true
            }
            JSON
        );

        $foo = $this->processor->process($request, FooInput::class);

        self::assertInstanceOf(FooInput::class, $foo);
        self::assertSame('It\'s a me, the foo', $foo->label);
        self::assertSame(42, $foo->value);
        self::assertSame(['the-foo', 'not-a-bar'], $foo->tags);
        self::assertSame(true, $foo->archived);
    }

    public function testProcessPartialInput()
    {
        $request = $this->createRequest(
            <<<JSON
            {
                "label": "It's a me, the foo"
            }
            JSON
        );

        $foo = $this->processor->process($request, FooInput::class);

        self::assertInstanceOf(FooInput::class, $foo);
        self::assertSame('It\'s a me, the foo', $foo->label);
        self::assertSame(null, $foo->value);
        self::assertSame([], $foo->tags);
        self::assertSame(false, $foo->archived);
    }

    public function testProcessEmptyInput()
    {
        $request = $this->createRequest(
            <<<JSON
            {
            }
            JSON
        );

        self::expectException(Exception::class);

        $this->processor->process($request, FooInput::class);
    }

    public function testProcessInvalidInput()
    {
        $request = $this->createRequest(
            <<<JSON
            {
                "label": 57,
                "tags": "expected an array, got a string",
                "archived": null
            }
            JSON
        );

        self::expectException(Exception::class);

        $this->processor->process($request, FooInput::class);
    }

    private function createRequest(string $json): Request
    {
        return Request::create(uri: 'test', server: [
            'CONTENT_TYPE' => 'application/json'
        ], content: $json);
    }
}
