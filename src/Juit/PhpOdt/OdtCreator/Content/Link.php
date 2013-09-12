<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;

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
        $element = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:a', $this->text);
        $element->setAttributeNS(ContentFile::NAMESPACE_XLINK, 'xlink:type', 'simple');
        $element->setAttributeNS(ContentFile::NAMESPACE_XLINK, 'xlink:href', $this->url);
        $domElement->appendChild($element);
    }
}
