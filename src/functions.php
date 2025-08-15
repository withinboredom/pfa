<?php


/**
 * Generate a closure that can accept partial function application
 *
 * @param Closure $first
 * @return Closure
 */
function p(Closure $first): Closure {
    return static function(...$steps) use ($first) {
        return static function(...$args) use ($first, $steps) {
            $argIndex = 0;
            foreach ($steps as $name => &$step) {
                if ($step === _ && is_numeric($name)) {
                    $step = $args[$argIndex++];
                } elseif($step === _ && is_string($name)) {
                    $step = $args[$name] ?? $args[$argIndex++];
                }
            }

            return $first(...$steps);
        };
    };
}

const _ = new stdClass();