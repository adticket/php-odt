<?php

namespace ODTCreator\Style;

class ParagraphStyle extends AbstractStyle
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->styleElement->setAttribute('style:family', 'paragraph');
    }

    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    protected function handleStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument)
    {
        // TODO: Implement handleStyleElement() method.
    }
}
