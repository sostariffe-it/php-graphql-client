<?php

namespace GraphQL\Util;

/**
 * Class StringLiteralFormatter
 *
 * @package GraphQL\Util
 */
class StringLiteralFormatter
{
    /**
     * Converts the value provided to the equivalent RHS value to be put in a file declaration
     *
     * @param string|int|float|bool $value
     *
     * @return string
     */
    public static function formatValueForRHS($value): string
    {
        if (is_string($value)) {
            $value = str_replace('"', '\"', $value);
            $value = "\"$value\"";
        } elseif (is_bool($value)) {
            if ($value) {
                $value = 'true';
            } else {
                $value = 'false';
            }
        } elseif ($value === null) {
            $value = 'null';
        } else {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public static function formatArrayForGQLQuery(array $array): string
    {
        $arrString = '[';
        $arrayRow = [];
        foreach ($array as $element) {
            $arrayRow[] = ConvertArgument::convertArgument($element);
        }
        $arrString .= implode(",", $arrayRow);
        $arrString .= ']';

        return $arrString;
    }

    /**
     * @param string $stringValue
     *
     * @return string
     */
    public static function formatUpperCamelCase(string $stringValue): string
    {
        if (strpos($stringValue, '_') === false) {
            return ucfirst($stringValue);
        }

        return str_replace('_', '', ucwords($stringValue, '_'));
    }

    /**
     * @param string $stringValue
     *
     * @return string
     */
    public static function formatLowerCamelCase(string $stringValue): string
    {
        return lcfirst(static::formatUpperCamelCase($stringValue));
    }
}