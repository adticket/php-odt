<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;
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
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $domElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:p');

        $style = $this->paragraphStyle ? $this->paragraphStyle : $this->styleFactory->getDefaultParagraphStyle();
        $domElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:style-name', $style->getStyleName());

        foreach ($this->contents as $text) {
            $text->renderTo($domDocument, $domElement);
        }

        if (!$parentElement) {
            $parentElement = $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0);
        }
        $parentElement->appendChild($domElement);
    }
}

