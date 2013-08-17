<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;
use ODTCreator\Style;
use ODTCreator\Style\ParagraphStyle;

class Paragraph extends AbstractElementWithContent
{
    /**
     * @var ParagraphStyle|null
     */
    private $paragraphStyle;

    public function __construct(ParagraphStyle $paragraphStyle = null)
    {
        $this->paragraphStyle = $paragraphStyle;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:p');
        if ($this->paragraphStyle) {
            $domElement->setAttributeNS(
                ContentFile::NAMESPACE_TEXT,
                'text:style-name',
                $this->paragraphStyle->getStyleName()
            );
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

