<?php

namespace Juit\PhpOdt\OdtCreator\Element;

interface Element
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null);
}
