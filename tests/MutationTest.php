<?php

namespace GraphQL\Tests;

use GraphQL\Mutation;
use PHPUnit\Framework\TestCase;

class MutationTest extends TestCase
{
    /**
     *
     */
    public function testMutationWithoutOperationType()
    {
        $mutation = new Mutation('createObject');

        $this->assertEquals(
            'mutation {
createObject 
}',
            (string) $mutation
        );
    }

    /**
     *
     */
    public function testMutationWithOperationType()
    {
        $mutation = new Mutation('mutation');

        $this->assertEquals(
            'mutation ',
            (string) $mutation
        );
    }

    /**
     * 
     */
    public function testMutationWithFields()
    {
        $mutation = (new Mutation('createObject'))
            ->setSelectionSet(
                [
                    'fieldOne',
                    'fieldTwo',
                ]
            );

        $this->assertEquals(
            'mutation {
createObject {
fieldOne
fieldTwo
}
}',
            (string) $mutation
        );
    }

    /**
     *
     */
    public function testMutationWithArgumentsAndFields()
    {
        $mutation = (new Mutation('createObject'))
            ->setSelectionSet(
                [
                    'fieldOne',
                    'fieldTwo',
                ]
            )->setArguments(
                [
                    'argOne' => 1,
                    'argTwo' => 'val'
                ]
            );

        $this->assertEquals(
            'mutation {
createObject(argOne: 1 argTwo: "val") {
fieldOne
fieldTwo
}
}',
            (string) $mutation
        );
    }
}