<?php

use Carbon\Carbon;

if (! function_exists('randomOrCreateFactory')) {
    function randomOrCreateFactory(string $class_name)
    {
        $class = new $class_name;

        if ($class::count()) {
            return $class::inRandomOrder()->first();
        }

        return $class::factory()->create();
    }
}

if (! function_exists('dateTimeFormat')) {
    function dateTimeFormat($value, $format = 'Y-m-d H:i:s')
    {
        return $value ? Carbon::parse($value)->format($format) : null;
    }
}
