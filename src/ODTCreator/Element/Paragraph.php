<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;
use ODTCreator\Content\Content;
use ODTCreator\Style\ParagraphStyle;
use ODTCreator\Style;

class Paragraph extends AbstractElement
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
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'p');

        foreach ($this->contents as $text) {
            $text->renderTo($domElement, $domDocument);
        }

        $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0)->appendChild($domElement);
    }
}

