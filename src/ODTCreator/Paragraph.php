<?php

namespace ODTCreator;

use ODTCreator\ParagraphContent\ParagraphContent;
use ODTCreator\Style\ParagraphStyle;

class Paragraph
{
    /**
     * @var Style\ParagraphStyle
     */
    private $style;

    /**
     * @var ParagraphContent[]
     */
    private $contents = array();

    public function __construct(ParagraphStyle $style = null)
    {
        $this->style = $style;
    }

    /**
     * @param ParagraphContent $content
     */
    public function addContent(ParagraphContent $content)
    {
        $this->contents[] = $content;
    }

    public function renderTo(\DOMDocument $domDocument)
    {
        $domElement = $domDocument->createElement('text:p');
        if ($this->style != null) {
            $domElement->setAttribute('text:style-name', $this->style->getStyleName());
        }

        foreach ($this->contents as $text) {
            $text->renderTo($domElement, $domDocument);
        }

        $domDocument->getElementsByTagName('office:text')->item(0)->appendChild($domElement);
    }
}

