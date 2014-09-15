<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Content\Content;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

abstract class AbstractElementWithContent implements Element
{
    /**
     * @var Content[]
     */
    protected $contents = array();

    /**
     * @var StyleFactory
     */
    protected $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @param string $content
     * @return Text
     */
    public function createTextElement($content)
    {
        $textElement      = new Text($content, $this->styleFactory);
        $this->contents[] = $textElement;

        return $textElement;
    }

    public function createLineBreak()
    {
        $this->contents[] = new LineBreak();
    }
}
