<?php

namespace Juit\PhpOdt\OdtCreator\Content;

interface Content
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $domElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $domElement);
}
