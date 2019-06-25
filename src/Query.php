<?php

namespace GraphQL;

use GraphQL\Exception\ArgumentException;
use GraphQL\Exception\InvalidSelectionException;
use GraphQL\Util\ConvertArgument;
use GraphQL\Util\JsonSerializableFormatter;
use GraphQL\Util\StringLiteralFormatter;

/**
 * Class Query
 *
 * @package GraphQL
 */
class Query
{
    /**
     * Stores the GraphQL query format
     *
     * @var string
     */
    protected const QUERY_FORMAT = "%s%s ##SELECTION_FORMAT##";
    protected const SELECTION_FORMAT = "{\n%s\n}";

    /**
     * Stores the name of the type of the operation to be executed on the GraphQL server
     *
     * @var string
     */
    protected const OPERATION_TYPE = 'query';

    /**
     * Stores the object being queried for
     *
     * @var string
     */
    private $object;

    /**
     * Stores the list of arguments used when querying data
     *
     * @var array
     */
    private $arguments;

    /**
     * Stores the selection set desired to get from the query, can include nested queries
     *
     * @var array
     */
    private $selectionSet;

    /**
     * Private member that's not accessible from outside the class, used internally to deduce if query is nested or not
     *
     * @var bool
     */
    private $isNested;

    /**
     * GQLQueryBuilder constructor.
     *
     * @param string $objectName
     */
    public function __construct(string $objectName)
    {
        $this->object       = $objectName;
        $this->arguments    = [];
        $this->selectionSet = [];
        $this->isNested     = false;
    }

    /**
     * Throwing exception when setting the arguments if they are incorrect because we can't throw an exception during
     * the execution of __ToString(), it's a fatal error in PHP
     *
     * @param array $arguments
     *
     * @return Query
     * @throws ArgumentException
     */
    public function setArguments(array $arguments): Query
    {
        // If one of the arguments does not have a name provided, throw an exception
        $nonStringArgs = array_filter(array_keys($arguments), function($element) {
            return !is_string($element);
        });
        if (!empty($nonStringArgs)) {
            throw new ArgumentException(
                'One or more of the arguments provided for creating the query does not have a key, which represents argument name'
            );
        }

        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param array $selectionSet
     *
     * @return Query
     * @throws InvalidSelectionException
     */
    public function setSelectionSet(array $selectionSet): Query
    {
        $nonStringsFields = array_filter($selectionSet, function($element) {
            return !is_string($element) && !$element instanceof Query;
        });
        if (!empty($nonStringsFields)) {
            throw new InvalidSelectionException(
                'One or more of the selection fields provided is not of type string or Query'
            );
        }

        $this->selectionSet = $selectionSet;

        return $this;
    }

    /**
     * @return string
     */
    protected function constructArguments(): string
    {
        // Return empty string if list is empty
        if (empty($this->arguments)) {
            return '';
        }

        // Construct arguments string if list not empty
        $constraintsString = '(';
        $first             = true;
        foreach ($this->arguments as $name => $value) {

            // Append space at the beginning if it's not the first item on the list
            if ($first) {
                $first = false;
            } else {
                $constraintsString .= ' ';
            }

            // Convert argument values to graphql string literal equivalent
            $value = ConvertArgument::convertArgument($value);

            // TODO: Handle cases where a non-string-convertible object is added to the arguments
            $constraintsString .= $name . ': ' . $value;
        }
        $constraintsString .= ')';

        return $constraintsString;
    }

    /**
     * @return string
     */
    protected function constructSelectionSet(): string
    {
        $attributesString = '';
        $first            = true;
        foreach ($this->selectionSet as $attribute) {

            // Append empty line at the beginning if it's not the first item on the list
            if ($first) {
                $first = false;
            } else {
                $attributesString .= "\n";
            }

            // If query is included in attributes set as a nested query
            if ($attribute instanceof Query) {
                $attribute->setAsNested();
            }

            // Append attribute to returned attributes list
            $attributesString .= $attribute;
        }

        return $attributesString;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $queryFormat = static::QUERY_FORMAT;
        $selectionFormat = static::SELECTION_FORMAT;

        if (!$this->isNested && $this->object !== static::OPERATION_TYPE) {
            $queryFormat = static::OPERATION_TYPE . " {\n" . static::QUERY_FORMAT . "\n}";
        }
        $argumentsString    = $this->constructArguments();
        $query = sprintf($queryFormat, $this->object, $argumentsString);

        $selectionSetString = $this->constructSelectionSet();
        $selection = "";
        if($selectionSetString !== ""){
            $selection = sprintf($selectionFormat, $selectionSetString);
        }

        return str_replace("##SELECTION_FORMAT##",$selection,$query);

    }

    /**
     *
     */
    private function setAsNested()
    {
        $this->isNested = true;
    }
}
