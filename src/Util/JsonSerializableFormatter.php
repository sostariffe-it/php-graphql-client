<?php


namespace GraphQL\Util;


class JsonSerializableFormatter
{
    public static function format(\JsonSerializable $object)
    {
        $array = $object->jsonSerialize();
        $return = "{" . PHP_EOL;
        foreach($array as $name => $value){
            $value = ConvertArgument::convertArgument($value);
            $return .= "{$name}: {$value}" . PHP_EOL;
        }
        $return .= "}";
        return $return;
    }
}