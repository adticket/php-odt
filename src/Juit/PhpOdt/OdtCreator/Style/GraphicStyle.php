<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;

class GraphicStyle extends AbstractStyle
{
    public function renderStyles(DOMDocument $document, DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttribute('style:family', 'graphic');

        $graphicPropertiesElement = $document->createElement('style:graphic-properties');
        $style->appendChild($graphicPropertiesElement);
        $graphicPropertiesElement->setAttribute('text:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttribute('svg:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttribute('svg:x', '0cm');
        $graphicPropertiesElement->setAttribute('svg:y', '0cm');
        $graphicPropertiesElement->setAttribute('fo:margin-left', '0.2cm');
        $graphicPropertiesElement->setAttribute('fo:margin-right', '0.2cm');
        $graphicPropertiesElement->setAttribute('fo:margin-top', '0.2cm');
        $graphicPropertiesElement->setAttribute('fo:margin-bottom', '0.2cm');
        $graphicPropertiesElement->setAttribute('style:wrap', 'parallel');
        $graphicPropertiesElement->setAttribute('style:number-wrapped-paragraphs', 'no-limit');
        $graphicPropertiesElement->setAttribute('style:wrap-contour', 'false');
        $graphicPropertiesElement->setAttribute('style:vertical-pos', 'from-top');
        $graphicPropertiesElement->setAttribute('style:vertical-rel', 'page');
        $graphicPropertiesElement->setAttribute('style:horizontal-pos', 'from-left');
        $graphicPropertiesElement->setAttribute('style:horizontal-rel', 'page');
        $graphicPropertiesElement->setAttribute('fo:padding', '0cm');
        $graphicPropertiesElement->setAttribute('fo:border', 'none');
    }
}
