<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;

class Frame extends AbstractElement
{
    /**
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument)
    {
        $frameElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:frame');
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

        $contentElement = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:p', 'FRAME CONTENT');
        $textBoxElement->appendChild($contentElement);
    }
}
