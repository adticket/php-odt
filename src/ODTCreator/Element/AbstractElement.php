<?php

namespace ODTCreator\Element;

use ODTCreator\Content\Content;

abstract class AbstractElement implements Element
{
    /**
     * @var Content[]
     */
    protected $contents = array();

    /**
     * @param Content $content
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;
    }

    /**
     * @param \DOMDocument $domDocument
     * @return void
     */
    abstract public function renderTo(\DOMDocument $domDocument);
}
