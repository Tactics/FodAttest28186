<?php

namespace Tactics\FodAttest28186\Enum;

use ReflectionClass;

/**
 * We write this class because this package has to be PHP7.4 compliant.
 * Once every project is on PHP8 (or we create a new tag that is PHP8 compliant),
 * we can get rid of this class and convert the child classes to actual Enums
 */
abstract class Enum
{
    /**
     * @var string|int
     */
    private $value;

    final private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string|int
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public static function cases(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    /**
     * @param $value
     *
     * @return static
     */
    public static function from($value)
    {
        $values = static::cases();
        if (!in_array($value, $values, true)) {
            throw new \InvalidArgumentException(sprintf("Invalid value for enum %s", static::class));
        }
        return new static($value);
    }
}
