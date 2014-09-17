<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
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
     * @param TextStyle $source
     * @param string $destinationName
     * @return TextStyle
     */
    public static function copy(TextStyle $source, $destinationName)
    {
        $destination = new self($destinationName);

        $destination->fontName    = $source->fontName;
        $destination->color       = $source->color ? clone $source->color : null;
        $destination->fontSize    = $source->fontSize ? clone $source->fontSize : null;
        $destination->isItalic    = $source->isItalic;
        $destination->isBold      = $source->isBold;
        $destination->isUnderline = $source->isUnderline;

        return $destination;
    }

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

    public function renderStyles(DOMDocument $document, DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'text');

        $element = $document->createElement('style:text-properties');
        $style->appendChild($element);
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
