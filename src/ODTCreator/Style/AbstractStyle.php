<?php

namespace ODTCreator\Style;

use Assert\Assertion as Assert;
use ODTCreator\ODTCreator;

abstract class AbstractStyle
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

    public function insertInto(\DOMDocument $domDocument)
    {
        $styleElement = $domDocument->createElement('style:style');
        $styleElement->setAttribute('style:name', $this->name);
        $domDocument->getElementsByTagName('office:styles')->item(0)->appendChild($styleElement);

        $this->handleStyleElement($styleElement, $domDocument);
    }

    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    abstract protected function handleStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument);
}
