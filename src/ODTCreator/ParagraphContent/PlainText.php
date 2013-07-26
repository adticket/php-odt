<?php

namespace ODTCreator\ParagraphContent;

class PlainText implements ParagraphContent
{
    /**
     * @var string
     */
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param \DOMElement $domElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function appendTo(\DOMElement $domElement, \DOMDocument $domDocument)
    {
        $domElement->appendChild($domDocument->createTextNode($this->content));
    }
}
