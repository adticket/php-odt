<?php

namespace Juit\PhpOdt\OdtCreator\Content;

class Link implements Content
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $text;

    public function __construct($url, $text)
    {
        $this->url = $url;
        $this->text = $text;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $domElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $domElement)
    {
        $element = $domDocument->createElement('text:a', $this->text);
        $element->setAttribute('xlink:type', 'simple');
        $element->setAttribute('xlink:href', $this->url);
        $domElement->appendChild($element);
    }
}
