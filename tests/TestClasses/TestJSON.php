<?php


namespace GraphQL\Tests\TestClasses;


class TestJSON implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        $value = new EnumTest("TWO");
        return [
            "test_name" => 12,
            "test_name_1" => "hi!",
            "enum" =>  $value,
            "nested" => new TestJSON2()
        ];
    }
}