<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\FontSize;

class TextStyle extends AbstractStyle
{
    /**
     * @var string|null
     */
    private $fontName = null;

    /**
     * @var null|\Juit\PhpOdt\OdtCreator\Value\Color
     */
    private $color = null;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\FontSize
     */
    private $fontSize = null;

    /**
     * @var bool
     */
    private $isItalic = false;

    /**
     * @var bool
     */
    private $isBold = false;

    /**
     * @var bool
     */
    private $isUnderline = false;

    /**
     * @param null|string $fontName
     */
    public function setFontName($fontName)
    {
        $this->fontName = $fontName;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Color $color
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
     * Make the text italic
     */
    public function setItalic()
    {
        $this->isItalic = true;
    }

    /**
     * Make the text underline
     */
    public function setUnderline()
    {
        $this->isUnderline = true;
    }

    /**
     * @param \DOMDocument $stylesDocument
     * @param \DOMElement $styleElement
     */
    protected function renderToStyleElement(\DOMDocument $stylesDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'text');

        $element = $stylesDocument->createElement('style:text-properties');
        $styleElement->appendChild($element);

        if ($this->fontName) {
            $element->setAttribute('style:font-name', $this->fontName);
        }

        if ($this->color) {
            $element->setAttribute('fo:color', $this->color->getHexCode());
        }

        if ($this->isItalic) {
            $element->setAttribute('fo:font-style', 'italic');
        }

        if ($this->isBold) {
            $element->setAttribute('fo:font-weight', 'bold');
        }

        if ($this->isUnderline) {
            $element->setAttribute('style:text-underline-style', 'solid');
            $element->setAttribute('style:text-underline-width', 'auto');
            $element->setAttribute('style:text-underline-color', 'font-color');
        }

        if ($this->fontSize) {
            $element->setAttribute('fo:font-size', $this->fontSize->getValue());
        }
    }
}
