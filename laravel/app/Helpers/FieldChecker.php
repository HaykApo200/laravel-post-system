<?php

namespace App\Helpers;

class FieldChecker
{
    /**
     * Check if all given fields in the array are null or empty.
     */
    public static function allFieldsAreEmpty(array $fields): bool
    {
        foreach ($fields as $field) {
            if (is_array($field)) {
                self::allFieldsAreEmpty($field);
            } else if (!($field === null || $field === '')) {
                return false;
            }
        }
        return true;
    }

    /**
     * Recursively remove all null values from the array.
     * Optionally remove empty arrays too.
     */
    public static function removeNulls(array $fields): array
    {
        $cleaned = [];

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $nested = self::removeNulls($value);

                // Only include if the cleaned nested array is not empty
                if (!empty($nested)) {
                    $cleaned[$key] = $nested;
                }
            } elseif (!is_null($value) && $value !== '') {
                $cleaned[$key] = $value;
            }
        }

        return $cleaned;
    }
}
