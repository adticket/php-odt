<?php

namespace ODTCreator\Content;

use ODTCreator\Document\Content as ContentFile;

class LineBreak implements Content
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parent
     * @return void
     */
    public function renderToStyles(\DOMDocument $domDocument, \DOMElement $parent = null)
    {
        // A line break does not have any styles
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $domElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $domElement)
    {
        $element = $domDocument->createElementNS(ContentFile::NAMESPACE_TEXT, 'text:line-break');
        $domElement->appendChild($element);
    }
}
