<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;

class GraphicStyle extends AbstractStyle
{
    public function renderStyles(DOMDocument $document, DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'graphic');

        $graphicPropertiesElement = $document->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:graphic-properties');
        $style->appendChild($graphicPropertiesElement);
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
    }
}
