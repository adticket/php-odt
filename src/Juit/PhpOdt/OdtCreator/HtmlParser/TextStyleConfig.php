<?php

namespace Juit\PhpOdt\OdtCreator\HtmlParser;

class TextStyleConfig
{
    /**
     * @var bool
     */
    private $isBold;

    /**
     * @var bool
     */
    private $isItalic;

    /**
     * @var bool
     */
    private $isUnderline;

    /**
     * @param bool $isBold
     * @param bool $isItalic
     * @param bool $isUnderline
     */
    public function __construct($isBold = false, $isItalic = false, $isUnderline = false)
    {
        $this->isBold = $isBold;
        $this->isItalic = $isItalic;
        $this->isUnderline = $isUnderline;
    }

    /**
     * @return TextStyleConfig
     */
    public function setBold()
    {
        return new self(true, $this->isItalic, $this->isUnderline);
    }

    /**
     * @return TextStyleConfig
     */
    public function setItalic()
    {
        return new self($this->isBold, true, $this->isUnderline);
    }

    public function setUnderline()
    {
        return new self($this->isBold, $this->isItalic, true);
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
}
