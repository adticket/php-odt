<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;

class Header extends AbstractElementWithContent
{
    /**
     * @var int
     */
    private $outlineLevel;

    public function __construct($outlineLevel = 1)
    {
        $this->outlineLevel = $outlineLevel;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToStyle(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {

    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'h');
        $domElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'outline-level', $this->outlineLevel);

        foreach ($this->contents as $text) {
            $text->renderTo($domDocument, $domElement);
        }

        if (!$parentElement) {
            $parentElement = $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0);
        }
        $parentElement->appendChild($domElement);
    }
}
