<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;

class LineBreak implements Content
{
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
