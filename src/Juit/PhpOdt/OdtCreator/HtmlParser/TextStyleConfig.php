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
     * @param bool $isBold
     * @param bool $isItalic
     */
    public function __construct($isBold = false, $isItalic = false)
    {
        $this->isBold = $isBold;
        $this->isItalic = $isItalic;
    }

    /**
     * @return TextStyleConfig
     */
    public function setBold()
    {
        return new self(true, $this->isItalic);
    }

    /**
     * @return TextStyleConfig
     */
    public function setItalic()
    {
        return new self($this->isBold, true);
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
}
