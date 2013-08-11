<?php

namespace ODTCreator\Style;

use ODTCreator\Document\Styles;
use ODTCreator\Value\Color;
use ODTCreator\Value\FontSize;

class TextStyle extends AbstractStyle
{
    /**
     * @var null|Color
     */
    private $color = null;

    /**
     * @var FontSize
     */
    private $fontSize = null;

    /**
     * @var bool
     */
    private $isBold = false;

    /**
     * @param \ODTCreator\Value\Color $color
     */
    public function setColor(Color $color)
    {
        $this->color = $color;
    }

    /**
     * @param FontSize $fontSize
     */
    public function setFontSize(FontSize $fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * Make the text bold
     */
    public function setBold()
    {
        $this->isBold = true;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    protected function renderToStyleElement(\DOMDocument $domDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:family', 'text');

        $element = $domDocument->createElement('style:text-properties');
        $styleElement->appendChild($element);

        if (null !== $this->color) {
            $element->setAttribute('fo:color', $this->color->getHexCode());
        }

        if ($this->isBold) {
            $element->setAttribute('fo:font-weight', 'bold');
        }

        if (null !== $this->fontSize) {
            $element->setAttribute('fo:font-size', $this->fontSize->getValue());
        }
    }
}
