<?php

namespace ODTCreator\Style;

class Factory
{
    /**
     * @var TextStyle[]
     */
    private $textStyles = array();

    /**
     * @var ParagraphStyle[]
     */
    private $paragraphStyles = array();

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

    /**
     * @return ParagraphStyle
     */
    public function createParagraphStyle()
    {
        $name = 'P' . (count($this->paragraphStyles) + 1);
        $paragraphStyle = new ParagraphStyle($name);
        $this->paragraphStyles[] = $paragraphStyle;

        return $paragraphStyle;
    }
}
