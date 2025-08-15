<?php


/**
 * Generate a closure that can accept partial function application
 *
 * @param Closure $first
 * @return Closure
 */
function p(Closure $first): Closure
{
    return static function (...$steps) use ($first) {
        $placeHolders = [];
        for($keys = array_keys($steps), $c = count($steps), $to = 0, $i = 0; $i < $c; $i++) {
            if($steps[$keys[$i]] === _) {
                $placeHolders[$to++] = $keys[$i];
                if(is_string($keys[$i])) {
                    $placeHolders[$keys[$i]] = $keys[$i];
                }
            }
        }

        return static function (...$args) use ($first, $steps, $placeHolders) {
            foreach($args as $name => $arg) {
                if($placeHolders[$name] ?? false) {
                    $steps[$placeHolders[$name]] = $arg;
                } else {
                    $steps[] = $arg;
                }
            }

            return $first(...$steps);
        };
    };
}

const _ = new stdClass();