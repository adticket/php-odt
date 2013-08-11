<?php

namespace ODTCreator\Style;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    protected function renderToStyleElement(\DOMDocument $domDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttribute('style:family', 'paragraph');
    }
}
