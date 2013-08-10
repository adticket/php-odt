<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;
use ODTCreator\Style\ParagraphStyle;
use ODTCreator\Style;

class Paragraph extends AbstractElementWithContent
{
    /**
     * @var Style\ParagraphStyle
     */
    private $style;

    public function __construct(ParagraphStyle $style = null)
    {
        $this->style = $style;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'p');

        foreach ($this->contents as $text) {
            $text->renderTo($domDocument, $domElement);
        }

        if (!$parentElement) {
            $parentElement = $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0);
        }
        $parentElement->appendChild($domElement);
    }
}

