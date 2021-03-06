<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Content\Content;
use Juit\PhpOdt\OdtCreator\Content\Image;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Content\TextFrame;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use SplFileInfo;

abstract class AbstractElementWithContent implements Element
{
    /**
     * @var Content[]
     */
    protected $contents = array();

    /**
     * @var StyleFactory
     */
    protected $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @param string $content
     * @return Text
     */
    public function createTextElement($content)
    {
        $textElement      = new Text($content, $this->styleFactory);
        $this->contents[] = $textElement;

        return $textElement;
    }

    public function createLineBreak()
    {
        $this->contents[] = new LineBreak();
    }

    /**
     * @param SplFileInfo $imagePath
     * @return Image
     */
    public function createImage(SplFileInfo $imagePath)
    {
        $name             = 'Image' . (count($this->contents) + 1);
        $image            = new Image($name, $imagePath, $this->styleFactory);
        $this->contents[] = $image;

        return $image;
    }

    /**
     * @return TextFrame
     */
    public function createTextFrame()
    {
        $name             = 'Frame' . (count($this->contents) + 1);
        $frame            = new TextFrame($name, $this->styleFactory);
        $this->contents[] = $frame;

        return $frame;
    }
}
