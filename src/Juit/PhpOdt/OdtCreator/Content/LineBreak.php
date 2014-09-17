<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use DOMDocument;
use DOMElement;

class LineBreak implements Content
{
    /**
     * @param DOMDocument $document
     * @param DOMElement $parent
     * @return void
     */
    public function renderTo(DOMDocument $document, DOMElement $parent)
    {
        $element = $document->createElement('text:line-break');
        $parent->appendChild($element);
    }
}
