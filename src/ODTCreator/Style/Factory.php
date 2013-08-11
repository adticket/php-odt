<?php

namespace ODTCreator\Style;

class Factory
{
    /**
     * @var TextStyle[]
     */
    private $textStyles = array();

    /**
     * @return TextStyle
     */
    public function createTextStyle()
    {
        $name = 'T' . (count($this->textStyles) + 1);
        $textStyle = new TextStyle($name);
        $this->textStyles[] = $textStyle;

        return $textStyle;
    }
}
