<?php

namespace ODTCreator\Element;

interface Element
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $parentElement = null);
}
