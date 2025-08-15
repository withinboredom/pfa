# PFA - Partial Function Application for PHP

A lightweight library for generating and using partial function application in PHP, designed to work seamlessly with
PHP’s upcoming pipe operator (`|>`).

## Installation

```bash
composer require withinboredom/pfa
```

## Requirements

- PHP 8.4+

## Usage

### Basic Partial Application

The `p()` function creates a closure that supports partial function application using placeholders:

```php
<?php
require_once 'vendor/autoload.php';

// Create a partially applied function
$strtoupper = p(strtoupper(...));
$result = $strtoupper('hello')();
// Result: 'HELLO'
```

### Using Placeholders

Use the `_` constant as a placeholder for arguments you want to provide later:

```php
<?php
// Partial application with array_map
$array_map = p(array_map(...));
$double = $array_map(static fn($x) => $x * 2, _);
$result = $double([1, 2, 3]);
// Result: [2, 4, 6]
```

### Named Placeholders

You can also use named parameters with placeholders:

```php
<?php
$add = p(fn($a, $b) => $a + $b);
$addOne = $add(a: 1, b: _);
$result = $addOne(2);
// Result: 3

// Or pass the named parameter
$result = $addOne(b: 2);
// Result: 3
```

## Working with PHP’s Pipe Operator

This library is designed to work beautifully with PHP’s upcoming pipe operator (`|>`):

```php
<?php
// Simple piping - single argument functions don't need p()
$result = 'hello world'
    |> strtoupper(...)
    |> p(str_replace(...))('WORLD', 'PHP', _);
// Result: 'HELLO PHP'

// More complex data transformation
$numbers = [1, 2, 3, 4, 5]
    |> p(array_filter(...))(_, fn($x) => $x % 2 === 0)
    |> p(array_map(...))(_, fn($x) => $x * 2)
    |> array_sum(...);
// Result: 12 (filtered [2, 4], mapped to [4, 8], summed)

// String processing pipeline
$text = "  Hello World  "
    |> trim(...)
    |> strtolower(...)
    |> ucwords(...)
    |> p(str_replace(...))(' ', '-', _);
// Result: "Hello-World"
```

### Advanced Pipeline Examples

```php
<?php
// Data validation and transformation pipeline
function validateAndTransform($data) {
    return $data
        |> p(array_filter(...))(_, fn($item) => !empty($item['name']))
        |> p(array_map(...))(_, fn($item) => [
            'name' => trim($item['name']),
            'email' => strtolower($item['email'] ?? ''),
            'age' => (int) ($item['age'] ?? 0)
        ])
        |> p(array_filter(...))(_, fn($item) => $item['age'] >= 18);
}

// HTTP response processing
$response = fetchApiData()
    |> p(json_decode(...))(_, true)
    |> p(array_get(...))(_, 'data', [])
    |> p(array_map(...))(_, fn($item) => new UserModel($item))
    |> p(array_filter(...))(_, fn($user) => $user->isActive());
```

## API Reference

### `p(Closure $function): Closure`

Creates a partially applicable function from the given closure.

**Parameters:**

- `$function`: The closure to make partially applicable

**Returns:**

- A closure that accepts partial arguments and returns another closure for the remaining arguments

### `_` Constant

A placeholder constant used to indicate where arguments should be filled in later.

## How It Works

The `p()` function creates a higher-order function that:

1. Accepts initial arguments (some of which can be placeholders)
2. Returns a closure that accepts the remaining arguments
3. Replaces placeholders with the provided arguments in order
4. Calls the original function with all arguments

Placeholders (`_`) are replaced with arguments in the order they’re provided, unless named parameters are used.

## License

MIT License. See LICENSE file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.