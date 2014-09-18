<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Value\BorderStyle;

class GraphicStyle extends AbstractStyle
{
    /**
     * @var BorderStyle|null
     */
    private $border = null;

    public function renderStyles(DOMDocument $document, DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttribute('style:family', 'graphic');

        $graphicProperties = $document->createElement('style:graphic-properties');
        $style->appendChild($graphicProperties);
        $graphicProperties->setAttribute('text:anchor-type', 'paragraph');
        $graphicProperties->setAttribute('svg:anchor-type', 'paragraph');
        $graphicProperties->setAttribute('svg:x', '0cm');
        $graphicProperties->setAttribute('svg:y', '0cm');
        $graphicProperties->setAttribute('fo:margin-left', '0.2cm');
        $graphicProperties->setAttribute('fo:margin-right', '0.2cm');
        $graphicProperties->setAttribute('fo:margin-top', '0.2cm');
        $graphicProperties->setAttribute('fo:margin-bottom', '0.2cm');
        $graphicProperties->setAttribute('style:wrap', 'parallel');
        $graphicProperties->setAttribute('style:number-wrapped-paragraphs', 'no-limit');
        $graphicProperties->setAttribute('style:wrap-contour', 'false');
        $graphicProperties->setAttribute('style:vertical-pos', 'from-top');
        $graphicProperties->setAttribute('style:vertical-rel', 'page');
        $graphicProperties->setAttribute('style:horizontal-pos', 'from-left');
        $graphicProperties->setAttribute('style:horizontal-rel', 'page');
        $graphicProperties->setAttribute('fo:padding', '0cm');
        $graphicProperties->setAttribute('fo:background-color', 'transparent');
        $graphicProperties->setAttribute('style:background-transparency', '100%');
        $graphicProperties->setAttribute('draw:fill', 'solid');

        if (null !== $this->border) {
            $border = $this->border->getValue();
        } else {
            $border = 'none';
        }
        $graphicProperties->setAttribute('fo:border', $border);
    }

    /**
     * @param BorderStyle|null $border
     */
    public function setBorder(BorderStyle $border = null)
    {
        $this->border = $border;
    }
}
