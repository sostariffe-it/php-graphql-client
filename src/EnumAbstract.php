<?php
namespace GraphQL;

use GraphQL\Exception\EnumError;

/**
 * Class EnumAbstract
 */
abstract class EnumAbstract
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    protected $possible_values;

    /**
     * EnumAbstract constructor.
     *
     * @param string $value
     * @throws EnumError
     */
    public function __construct(string $value)
    {
        $this->setPossibleValues();

        if (count($this->possible_values) === 0) {
            throw new EnumError("You must set the enum possible values on setPossibleValues");
        }

        $this->checkValue($value);

        $this->value = $value;
    }

    abstract public function setPossibleValues();

    /**
     * @param $value
     * @throws EnumError
     */
    private function checkValue($value)
    {
        if(!in_array($value, $this->possible_values)){
            throw new EnumError("Invalid value {$value} for the ENUM: {".__CLASS__."}. Possible values: " . implode(",", $this->possible_values));
        }
    }

    /**
     * Get Object value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Object Value
     *
     * @param mixed $value
     * @throws EnumError
     */
    public function setValue($value): void
    {
        $this->checkValue($value);
        $this->value = $value;
    }

    /**
     * Echo the value
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}