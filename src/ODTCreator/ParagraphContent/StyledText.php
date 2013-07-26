<?php

namespace ODTCreator\ParagraphContent;

use ODTCreator\Style\TextStyle;

class StyledText implements ParagraphContent
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var TextStyle
     */
    private $style;

    public function __construct($content, TextStyle $style)
    {
        $this->content = $content;
        $this->style = $style;
    }

    public function appendTo(\DOMElement $domElement, \DOMDocument $domDocument)
    {
        $span = $domDocument->createElement('text:span', $this->content);
        $span->setAttribute('text:style-name', $this->style->getStyleName());
        $domElement->appendChild($span);
    }
}
