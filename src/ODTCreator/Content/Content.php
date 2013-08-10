<?php

namespace ODTCreator\Content;

interface Content
{
    /**
     * @param \DOMElement $domElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function renderTo(\DOMElement $domElement, \DOMDocument $domDocument);
}
