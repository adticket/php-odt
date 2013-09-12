<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;

class GraphicStyle extends AbstractStyle
{
    /**
     * @param \DOMDocument $stylesDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    protected function renderToStyleElement(\DOMDocument $stylesDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'graphic');

        $graphicPropertiesElement = $stylesDocument->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:graphic-properties');

        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_TEXT, 'text:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_SVG, 'svg:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_SVG, 'svg:x', '0cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_SVG, 'svg:y', '0cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:margin-left', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:margin-right', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:margin-top', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:margin-bottom', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:wrap', 'parallel');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:number-wrapped-paragraphs', 'no-limit');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:wrap-contour', 'false');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-pos', 'from-top');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-rel', 'page');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-pos', 'from-left');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-rel', 'page');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:padding', '0cm');
        $graphicPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:border', 'none');

        $styleElement->appendChild($graphicPropertiesElement);
    }
}
