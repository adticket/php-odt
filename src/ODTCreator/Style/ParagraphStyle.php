<?php

namespace ODTCreator\Style;

use ODTCreator\Document\Styles;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @param \DOMDocument $stylesDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    protected function renderToStyleElement(\DOMDocument $stylesDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:family', 'paragraph');
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:parent-style-name', 'Standard');
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:master-page-name', 'First_20_Page');

        $element = $stylesDocument->createElementNS(Styles::NAMESPACE_STYLE, 'style:paragraph-properties');
        $styleElement->appendChild($element);

        $element->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:page-number', 'auto');
//        $element->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-top', '0.5cm');
//        $element->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-bottom', '0cm');
    }
}
