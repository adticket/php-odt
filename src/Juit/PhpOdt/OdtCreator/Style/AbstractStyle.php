<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;

abstract class AbstractStyle
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * return the name of this style
     * @return string
     */
    public function getStyleName()
    {
        return $this->name;
    }

    public function renderStyles(\DOMDocument $document, \DOMElement $parent)
    {}

    public function renderAutomaticStyles(\DOMDocument $contentDocument, \DOMElement $parentELement)
    {}

    /**
     * @param DOMDocument $document
     * @param DOMElement $parent
     * @return DOMElement
     */
    protected function createDefaultStyleElement(DOMDocument $document, DOMElement $parent)
    {
        $style = $document->createElement('style:style');
        $style->setAttribute('style:name', $this->name);
        $parent->appendChild($style);

        return $style;
    }
}
