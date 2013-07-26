<?php

namespace ODTCreator\ParagraphContent;

use ODTCreator\Style\TextStyle;

class Text
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var TextStyle|null
     */
    private $style;

    public function __construct($content, TextStyle $style = null)
    {
        $this->content = $content;
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return null|\ODTCreator\Style\TextStyle
     */
    public function getStyle()
    {
        return $this->style;
    }
}
