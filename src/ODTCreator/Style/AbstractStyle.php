<?php

namespace ODTCreator\Style;

use ODTCreator\Document\Styles;

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

    public function renderTo(\DOMDocument $domDocument)
    {
        $styleElement = $domDocument->createElementNS(Styles::NAMESPACE_STYLE, 'style:style');
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:name', $this->name);
        $domDocument->getElementsByTagNameNS(Styles::NAMESPACE_OFFICE, 'styles')->item(0)->appendChild($styleElement);

        $this->renderToStyleElement($domDocument, $styleElement);
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    abstract protected function renderToStyleElement(\DOMDocument $domDocument, \DOMElement $styleElement);
}
