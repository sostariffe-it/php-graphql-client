<?php


namespace GraphQL\Util;


use GraphQL\EnumAbstract;

class ConvertArgument
{
    /**
     * @param $value
     * @return string
     */
    public static function convertArgument($value): string
    {
        if (is_scalar($value)) {
            // Convert scalar value to its literal in graphql
            $value = StringLiteralFormatter::formatValueForRHS($value);
        } elseif ($value instanceof \JsonSerializable) {
            $value = JsonSerializableFormatter::format($value);
        } elseif (is_array($value)) {
            // Convert PHP array to its array representation in graphql arguments
            $value = StringLiteralFormatter::formatArrayForGQLQuery($value);
        } elseif ($value instanceof EnumAbstract) {
            $value = (string)$value;
        }
        return $value;
    }
}