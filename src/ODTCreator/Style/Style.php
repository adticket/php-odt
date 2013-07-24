<?php

namespace ODTCreator\Style;

use ODTCreator\ODTCreator;

class Style
{
    /**
     * The DOMDocument representing the styles xml file
     * @var \DOMDocument
     */
    protected $styleDocument;

    /**
     * The name of the style
     * @var string
     */
    protected $name;

    /**
     * The DOMElement representing this style
     * @var \DOMElement
     */
    protected $styleElement;

    /**
     * The constructor initializes the properties, then creates a <style:style>
     * element representing this specific style, and add it to <office:styles> element
     *
     * @param string $name
     * @internal param \DOMDocument $styleDoc
     */
    function __construct($name)
    {
        $this->styleDocument = ODTCreator::getInstance()->getStyleDocument();
        $this->name = $name;
        $this->styleElement = $this->styleDocument->createElement('style:style');
        $this->styleElement->setAttribute('style:name', $name);
        $this->styleDocument->getElementsByTagName('office:styles')->item(0)->appendChild($this->styleElement);
    }

    /**
     * return the name of this style
     * @return string
     */
    function getStyleName()
    {
        return $this->name;
    }

    public function setStyleName($name)
    {
        $this->name = $name;
    }
}
