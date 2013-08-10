<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;

class Frame implements Element
{
    /**
     * @var Element[]
     */
    protected $subElements = array();

    /**
     * @param Element $element
     */
    public function addSubElement(Element $element)
    {
        $this->subElements[] = $element;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $frameElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:frame');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_DRAW, 'draw:style-name', 'fr1');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-type', 'page');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-page-number', '1');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:x', '2cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:y', '2.7cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:width', '8.5cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:height', '4.5cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_DRAW, 'draw:z-index', '0');
        $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0)->appendChild($frameElement);

        $textBoxElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:text-box');
        $frameElement->appendChild($textBoxElement);

        foreach ($this->subElements as $subElement) {
            $subElement->renderTo($domDocument, $textBoxElement);
        }
    }
}
