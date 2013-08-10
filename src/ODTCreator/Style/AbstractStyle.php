<?php

namespace ODTCreator\Style;

use Assert\Assertion as Assert;

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

    public function renderTo(\DOMDocument $domDocument)
    {
        $styleElement = $domDocument->createElement('style:style');
        $styleElement->setAttribute('style:name', $this->name);
        $domDocument->getElementsByTagName('office:styles')->item(0)->appendChild($styleElement);

        $this->renderToStyleElement($styleElement, $domDocument);
    }

    /**
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    abstract protected function renderToStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument);
}
