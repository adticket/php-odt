<?php

namespace Juit\PhpOdt\OdtCreator;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;
use Juit\PhpOdt\OdtCreator\Document\File;
use Juit\PhpOdt\OdtCreator\Document\ManifestFile;
use Juit\PhpOdt\OdtCreator\Document\MetaFile;
use Juit\PhpOdt\OdtCreator\Document\SettingsFile;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Element\Element;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class OdtFile
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Document\StylesFile
     */
    private $styles;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Document\ContentFile
     */
    private $content;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->styleFactory = new StyleFactory();
        $this->styles = new StylesFile($this->styleFactory);
        $this->content = new ContentFile();
    }

    /**
     * @return \Juit\PhpOdt\OdtCreator\Style\StyleFactory
     */
    public function getStyleFactory()
    {
        return $this->styleFactory;
    }

    public function addElement(Element $element)
    {
        $this->content->addElement($element);
    }

    public function save(\SplFileInfo $targetFile)
    {
        $document = new \ZipArchive();
        $document->open($targetFile->getPathname(), \ZipArchive::OVERWRITE);

        $files = array(
            new ManifestFile(),
            $this->content,
            new MetaFile(),
            new SettingsFile(),
            $this->styles
        );
        foreach ($files as $file) {
            /** @var $file \Juit\PhpOdt\OdtCreator\Document\File */
            $document->addFromString($file->getRelativePath(), $file->render());
        }

        $document->close();
    }
}
