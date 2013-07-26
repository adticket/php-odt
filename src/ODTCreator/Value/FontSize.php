<?php

namespace ODTCreator\Value;

use ODTCreator\Style\StyleException;

class FontSize
{
    /**
     * @var string
     */
    private $fontSize;

    public function __construct($fontSize)
    {
        if (!preg_match('/^(\d*.?\d+(pt|cm|mm)?|\d+(.\d)*%)$/', $fontSize)) {
            throw new StyleException("Invalid font size value '$fontSize'");
        }

        $this->fontSize = $fontSize;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->fontSize;
    }
}
