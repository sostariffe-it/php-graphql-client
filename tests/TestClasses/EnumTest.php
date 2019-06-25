<?php


namespace GraphQL\Tests\TestClasses;


use GraphQL\EnumAbstract;

class EnumTest extends EnumAbstract
{

    public function setPossibleValues()
    {
        $this->possible_values = [
            "ONE",
            "TWO",
            "THREE"
        ];
    }

}