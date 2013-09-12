<?php

namespace Juit\PhpOdt\OdtCreator\Document;

class ManifestFile implements File
{
    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDocument();

        return $domDocument->saveXML();
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'META-INF/manifest.xml';
    }

    /**
     * @return \DOMDocument
     */
    private function createDocument()
    {
        $domDocument = new \DOMDocument();
        $domDocument->load(__DIR__ . '/templates/manifest.xml');

        return $domDocument;
    }
}
