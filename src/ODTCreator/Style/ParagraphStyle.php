<?php

namespace ODTCreator\Style;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    protected function appendToStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument)
    {
        $styleElement->setAttribute('style:family', 'paragraph');
    }
}
