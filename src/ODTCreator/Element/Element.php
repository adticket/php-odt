<?php

namespace ODTCreator\Element;

interface Element
{
    /**
     * @param \DOMDocument $domDocument
     * @return void
     */
    public function renderTo(\DOMDocument $domDocument);
}
