<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Value\Length;

class ImageStyle extends AbstractStyle
{
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';
    const WRAP_NONE = 'none';
    const WRAP_PARALLEL = 'parallel';

    /**
     * @var Length
     */
    private $marginTop;

    /**
     * @var Length
     */
    private $marginLeft;

    /**
     * @var Length
     */
    private $marginRight;

    /**
     * @var Length
     */
    private $marginBottom;

    /**
     * @var string
     */
    private $alignment = self::ALIGN_LEFT;

    /**
     * @var string
     */
    private $wrap = self::WRAP_NONE;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->marginTop    = new Length('0cm');
        $this->marginLeft   = new Length('0cm');
        $this->marginRight  = new Length('0cm');
        $this->marginBottom = new Length('0cm');
    }

    /**
     * @param Length $marginTop
     */
    public function setMarginTop(Length $marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @param Length $marginLeft
     */
    public function setMarginLeft(Length $marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @param Length $marginRight
     */
    public function setMarginRight(Length $marginRight)
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @param Length $marginBottom
     */
    public function setMarginBottom(Length $marginBottom)
    {
        $this->marginBottom = $marginBottom;
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

    public function renderAutomaticStyles(DOMDocument $document, DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttribute('style:family', 'graphic');
        $style->setAttribute('style:parent-style-name', 'Graphics');

        $graphicProperties = $document->createElement('style:graphic-properties');
        $style->appendChild($graphicProperties);
        $graphicProperties->setAttribute('fo:margin-left', $this->marginLeft->getValue());
        $graphicProperties->setAttribute('fo:margin-right', $this->marginRight->getValue());
        $graphicProperties->setAttribute('fo:margin-top', $this->marginTop->getValue());
        $graphicProperties->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
        if (self::WRAP_PARALLEL === $this->wrap) {
            $graphicProperties->setAttribute('style:wrap', 'parallel');
            $graphicProperties->setAttribute('style:number-wrapped-paragraphs', 'no-limit');
            $graphicProperties->setAttribute('style:wrap-contour', 'false');
        } else {
            $graphicProperties->setAttribute('style:wrap', 'none');
        }
        $graphicProperties->setAttribute('style:horizontal-pos', $this->alignment);
        $graphicProperties->setAttribute('style:horizontal-rel', 'paragraph');
        $graphicProperties->setAttribute('style:vertical-pos', 'top');
        $graphicProperties->setAttribute('style:vertical-rel', 'paragraph');
        $graphicProperties->setAttribute('style:shadow', 'none');
        $graphicProperties->setAttribute('draw:shadow-opacity', '100%');
        $graphicProperties->setAttribute('style:mirror', 'none');
        $graphicProperties->setAttribute('fo:clip', 'rect(0cm, 0cm, 0cm, 0cm)');
        $graphicProperties->setAttribute('draw:luminance', '0%');
        $graphicProperties->setAttribute('draw:contrast', '0%');
        $graphicProperties->setAttribute('draw:red', '0%');
        $graphicProperties->setAttribute('draw:green', '0%');
        $graphicProperties->setAttribute('draw:blue', '0%');
        $graphicProperties->setAttribute('draw:gamma', '100%');
        $graphicProperties->setAttribute('draw:color-inversion', 'false');
        $graphicProperties->setAttribute('draw:image-opacity', '100%');
        $graphicProperties->setAttribute('draw:color-mode', 'standard');
    }
}
