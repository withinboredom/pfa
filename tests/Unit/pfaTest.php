<?php

it('works', function() {
    $strtoupper = p(strtoupper(...));
    expect($strtoupper('hello')())->toBe('HELLO');
});

it('chains them together', function() {
    $this->markTestSkipped();
    $strtoupper = p(strtoupper(...));
    $ucfirst = p(lcfirst(...));

    //expect('hello world' |> $strtoupper(_) |> $ucfirst(_))->toBe('hELLO WORLD');
});

it('allows for placeholders', function() {
    $array_map = p(array_map(...));
    $array_map = $array_map(static fn($x) => $x * 2, _);
    expect($array_map([1, 2, 3]))->toBe([2, 4, 6]);
});

it('does handle extras', function() {
    $fn = p(fn(...$args) => $args);
    $fn = $fn(1, _, _);
    expect($fn(2, 3, 4))->toBe([1, 2, 3, 4]);
});

it('handles named placeholders', function() {
    $fn = p(fn($a, $b) => $a + $b);
    $fn = $fn(a: 1, b: _);
    expect($fn(2))->toBe(3);
    expect($fn(b: 2))->toBe(3);
});

it('can nest them', function() {
    // create a partial application compatible closure
    $str_replace = p(str_replace(...));
    // create a partial application using a bare underscore to annotate missing arguments
    $dash_replace = $str_replace('-', _, _);
    // and create another one based on the previous one
    $snake_case = p($dash_replace)('_', _);

    expect($snake_case('hello-world'))->toBe('hello_world');
});