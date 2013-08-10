<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;

class Header extends AbstractElement
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
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'h');
        $domElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'outline-level', $this->outlineLevel);

        foreach ($this->contents as $text) {
            $text->renderTo($domElement, $domDocument);
        }

        $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0)->appendChild($domElement);
    }
}
