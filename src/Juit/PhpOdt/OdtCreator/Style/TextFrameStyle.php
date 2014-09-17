<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;

class TextFrameStyle extends AbstractStyle
{
    public function renderAutomaticStyles(DOMDocument $content, DOMElement $parent)
    {
        $style = $content->createElement('style:style');
        $parent->appendChild($style);
        $style->setAttribute('style:name', $this->name);
        $style->setAttribute('style:family', 'graphic');
        $style->setAttribute('style:parent-style-name', 'Frame');

        $graphicProperties = $content->createElement('style:graphic-properties');
        $style->appendChild($graphicProperties);

        $graphicProperties->setAttribute('fo:margin-left', '0cm');
        $graphicProperties->setAttribute('fo:margin-right', '0cm');
        $graphicProperties->setAttribute('fo:margin-top', '0cm');
        $graphicProperties->setAttribute('fo:margin-bottom', '0cm');

        $graphicProperties->setAttribute('style:wrap', 'none');
        $graphicProperties->setAttribute('style:vertical-pos', 'top');
        $graphicProperties->setAttribute('style:vertical-rel', 'paragraph-content');
        $graphicProperties->setAttribute('style:horizontal-pos', 'left');
        $graphicProperties->setAttribute('style:horizontal-rel', 'paragraph');

        $graphicProperties->setAttribute('fo:padding', '0cm');
        $graphicProperties->setAttribute('fo:border', 'none');
        $graphicProperties->setAttribute('style:shadow', 'none');
        $graphicProperties->setAttribute('draw:shadow-opacity', '100%');
        $graphicProperties->setAttribute('style:flow-with-text', 'true');

//        $graphicProperties->setAttribute('fo:border', '0.06pt solid #ff0000');
    }
}
