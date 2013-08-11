<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;
use ODTCreator\Style;

class Paragraph extends AbstractElementWithContent
{
    /**
     * @var string|null
     */
    private $styleName;

    public function __construct($styleName = null)
    {
        $this->styleName = $styleName;
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
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:p');
        if ($this->styleName) {
            $domElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:style-name', $this->styleName);
        }

        foreach ($this->contents as $text) {
            $text->renderTo($domDocument, $domElement);
        }

        if (!$parentElement) {
            $parentElement = $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0);
        }
        $parentElement->appendChild($domElement);
    }
}

