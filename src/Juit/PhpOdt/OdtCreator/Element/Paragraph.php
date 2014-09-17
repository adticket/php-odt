<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Juit\PhpOdt\OdtCreator\Style\ParagraphStyle;

class Paragraph extends AbstractElementWithContent
{
    /**
     * @var \Juit\PhpOdt\OdtCreator\Style\ParagraphStyle|null
     */
    private $paragraphStyle;

    /**
     * @return ParagraphStyle
     */
    public function getStyle()
    {
        if (null === $this->paragraphStyle) {
            $this->paragraphStyle = $this->styleFactory->createParagraphStyle();
        }

        return $this->paragraphStyle;
    }

    /**
     * @param DOMDocument $document
     * @param DOMElement|null $parent
     * @return void
     */
    public function renderToContent(DOMDocument $document, DOMElement $parent = null)
    {
        $paragraph = $document->createElement('text:p');

        $style = $this->paragraphStyle ? $this->paragraphStyle : $this->styleFactory->getDefaultParagraphStyle();
        $paragraph->setAttribute('text:style-name', $style->getStyleName());

        foreach ($this->contents as $text) {
            $text->renderTo($document, $paragraph);
        }

        if (!$parent) {
            $xPath = new DOMXPath($document);
            $parent = $xPath->query('//office:text')->item(0);
        }
        $parent->appendChild($paragraph);
    }
}

