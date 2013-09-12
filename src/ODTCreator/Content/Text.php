<?php

namespace OdtCreator\Content;

use OdtCreator\Style\TextStyle;

class Text implements Content
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var TextStyle|null
     */
    private $style;

    public function __construct($content, TextStyle $style = null)
    {
        $this->content = $content;
        $this->style = $style;
    }

    public function renderTo(\DOMDocument $contentDocument, \DOMElement $parent)
    {
        if ($this->style) {
            $span = $contentDocument->createElement('text:span', $this->content);
            $span->setAttribute('text:style-name', $this->style->getStyleName());
            $parent->appendChild($span);
        } else {
            $parent->appendChild($contentDocument->createTextNode($this->content));
        }
    }
}
