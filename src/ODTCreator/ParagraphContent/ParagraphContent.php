<?php

namespace ODTCreator\ParagraphContent;

interface ParagraphContent
{
    /**
     * @param \DOMElement $domElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function appendTo(\DOMElement $domElement, \DOMDocument $domDocument);
}
