<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Style\ImageStyle;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class Image implements Content
{
    /**
     * @var \SplFileInfo
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

    public function __construct(\SplFileInfo $imagePath, StyleFactory $styleFactory)
    {
        $this->imagePath    = $imagePath;
        $this->styleFactory = $styleFactory;
        $this->style        = $styleFactory->createImageStyle();
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
        $frame->setAttribute('draw:name', 'Image1');
        $frame->setAttribute('text:anchor-type', 'paragraph');
        $frame->setAttribute('svg:width', '6.426cm');
        $frame->setAttribute('svg:height', '1.346cm');
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
}
