<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Content\Content;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;

abstract class AbstractElementWithContent implements Element
{
    /**
     * @var Content[]
     */
    protected $contents = array();

    /**
     * @param string $content
     * @param TextStyle $style
     * @return Text
     */
    public function createTextElement($content, TextStyle $style = null)
    {
        $textElement      = new Text($content, $style);
        $this->contents[] = $textElement;

        return $textElement;
    }

    public function createLineBreak()
    {
        $this->contents[] = new LineBreak();
    }
}
