<?php

namespace ODTCreator\Style;

use Assert\Assertion as Assert;
use ODTCreator\ODTCreator;

abstract class AbstractStyle
{
    /**
     * @var string
     */
    protected $name;

    /**
     * The constructor initializes the properties, then creates a <style:style>
     * element representing this specific style, and add it to <office:styles> element
     *
     * @param string $name
     */
    public function __construct($name)
    {
        // TODO: names must be unique
        Assert::string($name);
        Assert::notEmpty($name);

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

    public function appendTo(\DOMDocument $domDocument)
    {
        $styleElement = $domDocument->createElement('style:style');
        $styleElement->setAttribute('style:name', $this->name);
        $domDocument->getElementsByTagName('office:styles')->item(0)->appendChild($styleElement);

        $this->appendToStyleElement($styleElement, $domDocument);
    }

    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    abstract protected function appendToStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument);
}
