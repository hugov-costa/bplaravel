<?php

namespace App\Helpers;

class ArrayHelper
{
    /**
     * Remove empty strings without removing booleans.
     *
     * @param  array<mixed>  $data
     * @return array<mixed>
     */
    public static function removeEmptyStrings(array $data): array
    {
        return array_filter($data, function ($value) {
            return ! (is_string($value) && trim($value) === '');
        });
    }
}
