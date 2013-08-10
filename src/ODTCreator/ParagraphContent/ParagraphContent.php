<?php

namespace ODTCreator\ParagraphContent;

interface ParagraphContent
{
    /**
     * @param \DOMElement $domElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function renderTo(\DOMElement $domElement, \DOMDocument $domDocument);
}
