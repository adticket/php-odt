<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Style\ImageStyle;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Value\Length;
use SplFileInfo;

class Image implements Content
{
    /**
     * @var SplFileInfo
     */
    private $imagePath;

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var ImageStyle
     */
    private $style;

    /**
     * @var Length
     */
    private $width;

    /**
     * @var Length
     */
    private $height;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @param SplFileInfo $imagePath
     * @param StyleFactory $styleFactory
     * @internal param Length $width
     * @internal param Length $height
     */
    public function __construct($name, SplFileInfo $imagePath, StyleFactory $styleFactory)
    {
        $this->name         = $name;
        $this->imagePath    = $imagePath;
        $this->styleFactory = $styleFactory;
        $this->style        = $styleFactory->createImageStyle();
        $this->setWidth(new Length('5cm'));
    }

    public function setWidth(Length $width, $keepAspectRatio = true)
    {
        $this->width = $width;
        if ($keepAspectRatio) {
            $this->height = $width->multiplyBy($this->getAspectRatio());
        }
    }

    public function setHeight(Length $height, $keepAspectRatio = true)
    {
        $this->height = $height;
        if ($keepAspectRatio) {
            $this->width = $height->multiplyBy(1 / $this->getAspectRatio());
        }
    }

    /**
     * @return ImageStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param DOMDocument $contentDocument
     * @param DOMElement $parent
     * @return void
     */
    public function renderTo(DOMDocument $contentDocument, DOMElement $parent)
    {
        $frame = $contentDocument->createElement('draw:frame');

        $frame->setAttribute('draw:style-name', $this->style->getStyleName());
        $frame->setAttribute('draw:name', $this->name);
        $frame->setAttribute('text:anchor-type', 'paragraph');
        $frame->setAttribute('svg:width', $this->width->getValue());
        $frame->setAttribute('svg:height', $this->height->getValue());
        $frame->setAttribute('draw:z-index', '0');

        $parent->appendChild($frame);

        $image = $contentDocument->createElement('draw:image');

        $image->setAttribute('xlink:href', $this->imagePath->getPathname());
        $image->setAttribute('xlink:type', 'simple');
        $image->setAttribute('xlink:show', 'embed');
        $image->setAttribute('xlink:actuate', 'onLoad');
        $image->setAttribute('draw:filter-name', '&lt;All formats&gt;');

        $frame->appendChild($image);
    }

    /**
     * @return float
     */
    private function getAspectRatio()
    {
        $imageSize   = getimagesize($this->imagePath);
        $widthPixel  = $imageSize[0];
        $heightPixel = $imageSize[1];

        return $heightPixel / $widthPixel;
    }
}
