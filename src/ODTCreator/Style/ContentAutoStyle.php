<?php

namespace ODTCreator\Style;

use ODTCreator\ODTCreator;

class ContentAutoStyle
{
    /**
     * The DOMDocument representing the styles xml file
     * @var \DOMDocument
     */
    protected $contentDocument;

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
     * The constructor initializes the properties, then creates a <style:style> element , or an other element
     * if $elementNodeName is specified, representing this specific style, and add it to <office:automatic-styles> element
     *
     * @param string $name The style name
     * @param string|null $elementNodeName
     */
    function __construct($name, $elementNodeName = null)
    {
        $this->contentDocument = ODTCreator::getInstance()->getContentDocument();
        $this->name = $name;
        if ($elementNodeName == null) {
            $this->styleElement = $this->contentDocument->createElement('style:style');
        } else {
            $this->styleElement = $this->contentDocument->createElement($elementNodeName);
        }
        $this->styleElement->setAttribute('style:name', $name);
        $this->contentDocument->getElementsByTagName('office:automatic-styles')->item(0)->appendChild($this->styleElement);
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
