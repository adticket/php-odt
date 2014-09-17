<?php

namespace Juit\PhpOdt\OdtCreator\HtmlParser;

use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\FontSize;

class TextStyleConfig
{
    /**
     * @var bool
     */
    private $isBold = false;

    /**
     * @var bool
     */
    private $isItalic = false;

    /**
     * @var bool
     */
    private $isUnderline = false;

    /**
     * @var FontSize|null
     */
    private $fontSize = null;

    /**
     * @var string|null
     */
    private $fontName = null;

    /**
     * @var Color|null
     */
    private $fontColor = null;

    /**
     * @return TextStyleConfig
     */
    public function setBold()
    {
        $newInstance = clone $this;
        $newInstance->isBold = true;

        return $newInstance;
    }

    /**
     * @return TextStyleConfig
     */
    public function setItalic()
    {
        $newInstance = clone $this;
        $newInstance->isItalic = true;

        return $newInstance;
    }

    /**
     * @return TextStyleConfig
     */
    public function setUnderline()
    {
        $newInstance = clone $this;
        $newInstance->isUnderline = true;

        return $newInstance;
    }

    /**
     * @return boolean
     */
    public function isBold()
    {
        return $this->isBold;
    }

    /**
     * @return boolean
     */
    public function isItalic()
    {
        return $this->isItalic;
    }

    /**
     * @return boolean
     */
    public function isUnderline()
    {
        return $this->isUnderline;
    }

    /**
     * @return FontSize|null
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param FontSize $fontSize
     * @return TextStyleConfig
     */
    public function setFontSize(FontSize $fontSize)
    {
        $newInstance = clone $this;
        $newInstance->fontSize = $fontSize;

        return $newInstance;
    }

    /**
     * @return null|string
     */
    public function getFontName()
    {
        return $this->fontName;
    }

    /**
     * @param string $fontName
     * @return TextStyleConfig
     */
    public function setFontName($fontName)
    {
        $newInstance = clone $this;
        $newInstance->fontName = $fontName;

        return $newInstance;
    }

    /**
     * @return Color|null
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * @param Color|null $fontColor
     * @return \Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig
     */
    public function setFontColor($fontColor)
    {
        $newInstance = clone $this;
        $newInstance->fontColor = $fontColor;

        return $newInstance;
    }
}
