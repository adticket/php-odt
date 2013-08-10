<?php

namespace ODTCreator\Element;

interface Element
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToStyle(\DOMDocument $domDocument, \DOMElement $parentElement = null);

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null);
}
