<?php

namespace Juit\PhpOdt\OdtCreator\Value;

class Length
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $unit;

    public function __construct($value)
    {
        $matches = [];
        if (!preg_match('/^(?P<value>\d+(\.\d+){0,1})(?P<unit>(cm|pt))$/', $value, $matches)) {
            throw new \InvalidArgumentException("Unrecognized length value '$value'");
        }

        $this->value = (float)$matches['value'];
        $this->unit  = $matches['unit'];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value . $this->unit;
    }

    /**
     * @param float $aspectRatio
     * @return Length
     */
    public function multiplyBy($aspectRatio)
    {
        $newValue = round($this->value * $aspectRatio, 3);

        return new self($newValue . $this->unit);
    }

}
