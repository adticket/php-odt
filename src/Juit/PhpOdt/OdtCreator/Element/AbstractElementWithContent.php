<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Content\Content;

abstract class AbstractElementWithContent implements Element
{
    /**
     * @var Content[]
     */
    protected $contents = array();

    /**
     * @param \Juit\PhpOdt\OdtCreator\Content\Content $content
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;
    }
}
