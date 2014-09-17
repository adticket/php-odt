<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;
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
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'graphic');
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:parent-style-name', 'Graphics');

        $graphicProperties = $document->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:graphic-properties');
        $style->appendChild($graphicProperties);
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-left', $this->marginLeft->getValue());
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-right', $this->marginRight->getValue());
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-top', $this->marginTop->getValue());
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-bottom', $this->marginBottom->getValue());
        if (self::WRAP_PARALLEL === $this->wrap) {
            $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'wrap', 'parallel');
            $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'number-wrapped-paragraphs', 'no-limit');
            $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'wrap-contour', 'false');
        } else {
            $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'wrap', 'none');
        }
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-pos', $this->alignment);
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-rel', 'paragraph');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-pos', 'top');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-rel', 'paragraph');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:shadow', 'none');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:shadow-opacity', '100%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:mirror', 'none');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:clip', 'rect(0cm, 0cm, 0cm, 0cm)');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:luminance', '0%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:contrast', '0%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:red', '0%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:green', '0%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:blue', '0%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:gamma', '100%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:color-inversion', 'false');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:image-opacity', '100%');
        $graphicProperties->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:color-mode', 'standard');
    }
}
