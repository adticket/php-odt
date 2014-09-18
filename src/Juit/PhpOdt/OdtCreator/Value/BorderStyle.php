<?php

namespace Juit\PhpOdt\OdtCreator\Value;

class BorderStyle
{
    /**
     * @var Length
     */
    private $width;

    /**
     * @var Color
     */
    private $color;

    public function __construct(Length $width, Color $color)
    {
        $this->width = $width;
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->width->getValue() . ' solid ' . $this->color->getHexCode();
    }
} 
