<?php

namespace GraphQL\Tests;

use GraphQL\Exception\EmptySelectionSetException;
use GraphQL\Query;
use GraphQL\QueryBuilder\QueryBuilder;
use GraphQL\RawObject;
use GraphQL\Tests\TestClasses\EnumTest;
use GraphQL\Tests\TestClasses\TestJSON;
use PHPUnit\Framework\TestCase;

/**
 * This test case is responsible for testing the QueryBuilder and AbstractQueryBuilder classes
 *
 * Class QueryBuilderTest
 *
 * @package GraphQL\Tests
 */
class QueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder('Object');
    }

    /**
     * @covers \GraphQL\QueryBuilder\QueryBuilder::__construct
     * @covers \GraphQL\QueryBuilder\AbstractQueryBuilder::__construct
     */
    public function testConstruct()
    {
        $builder = new QueryBuilder('Object');
        $builder->selectField('field_one');
        $this->assertEquals(
            'query {
Object {
field_one
}
}',
            (string) $builder->getQuery()
        );
    }

    /**
     * @covers \GraphQL\QueryBuilder\QueryBuilder::getQuery
     * @covers \GraphQL\QueryBuilder\QueryBuilder::selectField
     * @covers \GraphQL\QueryBuilder\AbstractQueryBuilder::selectField
     */
    public function testSelectScalarFields()
    {
        $this->queryBuilder->selectField('field_one');
        $this->queryBuilder->selectField('field_two');
        $this->assertEquals(
            'query {
Object {
field_one
field_two
}
}',
            (string) $this->queryBuilder->getQuery()
        );
    }

    /**
     * @covers \GraphQL\QueryBuilder\QueryBuilder::getQuery
     * @covers \GraphQL\QueryBuilder\QueryBuilder::selectField
     */
    public function testSelectNestedQuery()
    {
        $this->queryBuilder->selectField(
            (new Query('Nested'))
                ->setSelectionSet(['some_field'])
        );
        $this->assertEquals(
            'query {
Object {
Nested {
some_field
}
}
}',
            (string) $this->queryBuilder->getQuery()
        );
    }

    public function testSelectNestedQueryBuilder()
    {
        $this->queryBuilder->selectField(
            (new QueryBuilder('Nested'))
                ->selectField('some_field')
        );
        $this->assertEquals(
            'query {
Object {
Nested {
some_field
}
}
}',
            (string) $this->queryBuilder->getQuery()
        );
    }

    /**
     * @covers \GraphQL\QueryBuilder\QueryBuilder::getQuery
     * @covers \GraphQL\QueryBuilder\QueryBuilder::setArgument
     * @covers \GraphQL\QueryBuilder\AbstractQueryBuilder::setArgument
     */
    public function testSelectArguments()
    {
        $this->queryBuilder->selectField('field');
        $this->queryBuilder->setArgument('str_arg', 'value');
        $this->assertEquals(
            'query {
Object(str_arg: "value") {
field
}
}',
            (string) $this->queryBuilder->getQuery()
        );

        $this->queryBuilder->setArgument('bool_arg', true);
        $this->assertEquals(
            'query {
Object(str_arg: "value" bool_arg: true) {
field
}
}',
            (string) $this->queryBuilder->getQuery()
        );

        $this->queryBuilder->setArgument('int_arg', 10);
        $this->assertEquals(
            'query {
Object(str_arg: "value" bool_arg: true int_arg: 10) {
field
}
}',
            (string) $this->queryBuilder->getQuery()
        );

        $this->queryBuilder->setArgument('array_arg', ['one', 'two', 'three']);
        $this->assertEquals(
            'query {
Object(str_arg: "value" bool_arg: true int_arg: 10 array_arg: ["one", "two", "three"]) {
field
}
}',
            (string) $this->queryBuilder->getQuery()
        );

        $this->queryBuilder->setArgument('input_object_arg', new RawObject('{field_not: "x"}'));
        $this->assertEquals(
            'query {
Object(str_arg: "value" bool_arg: true int_arg: 10 array_arg: ["one", "two", "three"] input_object_arg: {field_not: "x"}) {
field
}
}',
            (string) $this->queryBuilder->getQuery()
        );
    }

    /**
     * @covers \GraphQL\QueryBuilder\QueryBuilder::getQuery
     * @covers \GraphQL\QueryBuilder\QueryBuilder::setArgument
     * @covers \GraphQL\QueryBuilder\AbstractQueryBuilder::setArgument
     * @covers \GraphQL\QueryBuilder\QueryBuilder::selectField
     * @covers \GraphQL\QueryBuilder\AbstractQueryBuilder::selectField
     */
    public function testSetTwoLevelArguments()
    {
        $this->queryBuilder->selectField(
            (new QueryBuilder('Nested'))
                ->selectField('some_field')
                ->selectField('another_field')
                ->setArgument('nested_arg', [1, 2, 3])
        )
        ->setArgument('outer_arg', 'outer val');
        $this->assertEquals(
            'query {
Object(outer_arg: "outer val") {
Nested(nested_arg: [1, 2, 3]) {
some_field
another_field
}
}
}',
            (string) $this->queryBuilder->getQuery()
        );
    }

    public function testEnumQuery()
    {

        $enum = new EnumTest("ONE");

        $this->queryBuilder = new QueryBuilder("GetEnum");
        $this->queryBuilder->selectField("obj")
            ->setArgument('enum', $enum);

        $this->assertEquals('query {
GetEnum(enum: ONE) {
obj
}
}',
            (string)$this->queryBuilder->getQuery());
    }

    public function testNoSelectionQuery()
    {

        $enum = new EnumTest("ONE");

        $this->queryBuilder = new QueryBuilder("GetEnum");
        $this->queryBuilder
            ->selectField(null)
            ->setArgument('enum', $enum);

        $this->assertEquals('query {
GetEnum(enum: ONE) 
}',
            (string)$this->queryBuilder->getQuery());
    }


    public function testJsonSerializable()
    {

        $obj = new TestJSON();

        $this->queryBuilder = new QueryBuilder("GetJSON");
        $this->queryBuilder
            ->selectField(null)
            ->setArgument('jsonable', [ $obj ]);
        echo (string)$this->queryBuilder->getQuery();
        exit;


        $this->assertEquals('query {
GetJSON(jsonable: {
test_name: 12
test_name_1: "hi!"
enum: TWO
nested: {
test_name: 34
}
}) 
}',
            (string)$this->queryBuilder->getQuery());
    }
}