<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\Length;

class TextFrameStyle extends AbstractStyle
{
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';
    const WRAP_NONE = 'none';
    const WRAP_PARALLEL = 'parallel';

    /**
     * @var string
     */
    private $alignment = self::ALIGN_LEFT;

    /**
     * @var string
     */
    private $wrap = self::WRAP_NONE;

    /**
     * @var Length|null
     */
    private $borderWidth = null;

    /**
     * @var Color
     */
    private $borderColor;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->borderColor = new Color('#000000');
    }

    public function setAlignLeft()
    {
        $this->alignment = self::ALIGN_LEFT;
    }

    public function setAlignCenter()
    {
        $this->alignment = self::ALIGN_CENTER;
    }

    public function setAlignRight()
    {
        $this->alignment = self::ALIGN_RIGHT;
    }

    public function setWrapNone()
    {
        $this->wrap = self::WRAP_NONE;
    }

    public function setWrapParallel()
    {
        $this->wrap = self::WRAP_PARALLEL;
    }

    /**
     * @param Length|null $borderWidth
     */
    public function setBorderWidth(Length $borderWidth = null)
    {
        $this->borderWidth = $borderWidth;
    }

    /**
     * @param Color $borderColor
     */
    public function setBorderColor(Color $borderColor)
    {
        $this->borderColor = $borderColor;
    }

    public function renderAutomaticStyles(DOMDocument $content, DOMElement $parent)
    {
        $style = $content->createElement('style:style');
        $parent->appendChild($style);
        $style->setAttribute('style:name', $this->name);
        $style->setAttribute('style:family', 'graphic');
        $style->setAttribute('style:parent-style-name', 'Frame');

        $graphicProperties = $content->createElement('style:graphic-properties');
        $style->appendChild($graphicProperties);

        $graphicProperties->setAttribute('fo:margin-left', '0cm');
        $graphicProperties->setAttribute('fo:margin-right', '0cm');
        $graphicProperties->setAttribute('fo:margin-top', '0cm');
        $graphicProperties->setAttribute('fo:margin-bottom', '0cm');

        $graphicProperties->setAttribute('style:wrap', $this->wrap);
        $graphicProperties->setAttribute('style:vertical-pos', 'top');
        $graphicProperties->setAttribute('style:vertical-rel', 'paragraph-content');
        $graphicProperties->setAttribute('style:horizontal-pos', $this->alignment);
        $graphicProperties->setAttribute('style:horizontal-rel', 'paragraph');

        $graphicProperties->setAttribute('fo:padding', '0cm');
        $graphicProperties->setAttribute('fo:border', 'none');
        $graphicProperties->setAttribute('style:shadow', 'none');
        $graphicProperties->setAttribute('draw:shadow-opacity', '100%');
        $graphicProperties->setAttribute('style:flow-with-text', 'true');

        if (null !== $this->borderWidth) {
            $graphicProperties->setAttribute(
                'fo:border',
                "{$this->borderWidth->getValue()} solid {$this->borderColor->getHexCode()}"
            );
        }
    }
}
