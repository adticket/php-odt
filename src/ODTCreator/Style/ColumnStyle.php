<?php

namespace ODTCreator\Style;

use ODTCreator\Common;

class ColumnStyle extends ContentAutoStyle
{
    private $colProp;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->styleElement->setAttribute('style:family', 'table-column');
        $this->colProp = $this->contentDocument->createElement('style:table-column-properties');
        $this->styleElement->appendChild($this->colProp);
    }

    /**
     * Sets the width of the table.
     *
     * @param string $width
     * @throws StyleException
     */
    public function setWidth($width)
    {
        if (Common::isLengthValue($width, true) || Common::isPercentage($width)) {
            if (Common::isLengthValue($width, true)) {
                $this->colProp->setAttribute('style:column-width', $width);
            } else if (Common::isPercentage($width)) {
                $this->colProp->setAttribute('style:rel-column-width', $width);
            }
        } else {
            throw new StyleException('Invalid table-width value');
        }
    }

    /**
     * Specifies if the column width should be recalculated automatically if some content in the column changes.
     *
     * @param bool $optimalWidth
     * @throws StyleException
     */
    public function setOptimalWidth($optimalWidth)
    {
        if (is_bool($optimalWidth)) {
            $this->colProp->setAttribute('style:use-optimal-column-width', $optimalWidth);
        } else {
            throw new StyleException('Value must be boolean');
        }
    }

    /**
     * Insert a page or column break before a table column.
     *
     * @param integer $breakBefore Possible values: StyleConstants::(PAGE|COLUMN)
     * @throws StyleException
     */
    function setBreakBefore($breakBefore)
    {
        switch ($breakBefore) {
            case StyleConstants::PAGE:
                $breakBefore = 'page';
                break;
            case StyleConstants::COLUMN:
                $breakBefore = 'column';
                break;
            default:
                throw new StyleException('Invalid break-before value.');
        }
        $this->colProp->setAttribute('fo:break-before', $breakBefore);
    }

    /**
     * Insert a page or column break after a table column
     *
     * @param integer $breakAfter Possible values: StyleConstants::(PAGE|COLUMN)
     * @throws StyleException
     */
    function setBreakAfter($breakAfter)
    {
        switch ($breakAfter) {
            case StyleConstants::PAGE:
                $breakAfter = 'page';
                break;
            case StyleConstants::COLUMN:
                $breakAfter = 'column';
                break;
            default:
                throw new StyleException('Invalid break-after value.');
        }
        $this->colProp->setAttribute('fo:break-after', $breakAfter);
    }
}