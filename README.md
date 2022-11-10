# HIP: <ins>H</ins>TTP <ins>I</ins>nput <ins>P</ins>rocessor

This component aims to help validating HTTP request inputs, and processing them to instantiate an input class,
providing possibilities of robust code analysis.

For instance, let us consider a route `POST @ /foos` expecting following signature:
```typescript
type foo = {
    label: string
    value: int
    tags: [string]
    archived: boolean
}
```

On the server, the PHP code handling this input would simply need to sign the controller method with a type describing
expected data structure:
```php
class FooInput
{
    public string $label;
    public int $value;
    /** @var array<string> */
    public array $tags;
    public bool $archived;
}

class FooController
{
    public function post(FooInput $fooInput)
    {
        // ...
    } 
}
```

Therefore, the need is to implement a resolver that would be able to instantiate a `FooInput` based on the content of
the HTTP request. In the same time, it should be able to validate the input, and return an exception if any constraint
has been violated!
Therefore, it would be natural to be able to add any validation constraint on the type, for instance:
```php
class FooInput
{
    #[NotEmpty]
    public string $label;
    #[Positive(strict: true)]
    public int $value;
    /** @var array<string> */
    public array $tags;
    public bool $archived;
}
```

Some parameters could also be optional or given a default value:

```php
class FooInput
{
    #[NotEmpty]
    public string $label;
    #[Positive(strict: true)]
    public ?int $value;
    /** @var array<string> */
    public array $tags;
    public bool $archived = false;
}
```

Here, `$value` is now optional (null if not provided), and `$archived` defaults to `false` if not provided.
