<?php

namespace ODTCreator\Style;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    protected function handleStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument)
    {
        $styleElement->setAttribute('style:family', 'paragraph');
    }
}
