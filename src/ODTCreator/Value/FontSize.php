<?php

namespace ODTCreator\Value;

use ODTCreator\Common;
use ODTCreator\Style\StyleException;

class FontSize
{
    /**
     * @var string
     */
    private $fontSize;

    public function __construct($fontSize)
    {
        if (!Common::isNumeric($fontSize) && !Common::isPercentage($fontSize)) {
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
