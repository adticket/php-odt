<?php

namespace Juit\PhpOdt\OdtCreator\HtmlParser;

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
}
