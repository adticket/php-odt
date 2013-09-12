<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;

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

    public function renderTo(\DOMDocument $stylesDocument, \DOMElement $parentElement)
    {
        $element = $stylesDocument->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:style');
        $element->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:name', $this->name);
        $parentElement->appendChild($element);

        $this->renderToStyleElement($stylesDocument, $element);
    }

    /**
     * @param \DOMDocument $stylesDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    abstract protected function renderToStyleElement(\DOMDocument $stylesDocument, \DOMElement $styleElement);
}
