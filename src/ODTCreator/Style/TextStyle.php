<?php

namespace ODTCreator\Style;

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
     * @param \DOMElement $styleElement
     * @param \DOMDocument $domDocument
     * @return void
     */
    protected function renderToStyleElement(\DOMElement $styleElement, \DOMDocument $domDocument)
    {
        $styleElement->setAttribute('style:family', 'text');

        if (null !== $this->color) {
            $element = $domDocument->createElement('style:text-properties');
            $element->setAttribute('fo:color', $this->color->getHexCode());
            $styleElement->appendChild($element);
        }

        if ($this->isBold) {
            $element = $domDocument->createElement('style:text-properties');
            $element->setAttribute('fo:font-weight', 'bold');
            $styleElement->appendChild($element);
        }

        if (null !== $this->fontSize) {
            $element = $domDocument->createElement('style:text-properties');
            $element->setAttribute('fo:font-size', $this->fontSize->getValue());
            $styleElement->appendChild($element);
        }
    }
}
