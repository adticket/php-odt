<?php

namespace ODTCreator\Element;

use ODTCreator\Content\Content;

abstract class AbstractElementWithContent implements Element
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
}
