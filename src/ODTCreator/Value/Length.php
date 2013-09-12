<?php

namespace OdtCreator\Value;

class Length
{
    /**
     * @var string
     */
    private $value;

    public function __construct($value)
    {
        $this->assertIsValid($value);
        $this->value = $value;
    }

    private function assertIsValid($value)
    {
        if (!preg_match('/^\d+(\.\d+){0,1}(cm|pt)$/', $value)) {
            throw new \InvalidArgumentException("Unrecognized length value '$value'");
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}
