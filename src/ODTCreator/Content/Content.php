<?php

namespace ODTCreator\Content;

interface Content
{
    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $parent
     * @return void
     */
    public function renderToStyles(\DOMDocument $domDocument, \DOMElement $parent = null);

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $domElement
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument, \DOMElement $domElement);
}
