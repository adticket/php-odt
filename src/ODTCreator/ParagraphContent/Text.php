<?php

namespace ODTCreator\ParagraphContent;

use ODTCreator\Style\TextStyle;

class Text implements ParagraphContent
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

    public function appendTo(\DOMElement $domElement, \DOMDocument $domDocument)
    {
        if (null !== $this->style) {
            $span = $domDocument->createElement('text:span', $this->content);
            $span->setAttribute('text:style-name', $this->style->getStyleName());
            $domElement->appendChild($span);
        } else {
            $domElement->appendChild($domDocument->createTextNode($this->content));
        }
    }
}
