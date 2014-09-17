<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;

class Text implements Content
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var null|TextStyle
     */
    private $style = null;

    public function __construct($content, StyleFactory $styleFactory)
    {
        $this->content      = $content;
        $this->styleFactory = $styleFactory;
    }

    /**
     * @return TextStyle
     */
    public function getStyle()
    {
        if (null === $this->style) {
            $this->style = $this->styleFactory->createTextStyle();
        }

        return $this->style;
    }

    public function renderTo(DOMDocument $content, DOMElement $parent)
    {
        $style = $this->style ? $this->style : $this->styleFactory->getDefaultTextStyle();

        $span = $content->createElement('text:span', $this->content);
        $span->setAttribute('text:style-name', $style->getStyleName());
        $parent->appendChild($span);
    }
}
