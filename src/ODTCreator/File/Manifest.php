<?php

namespace ODTCreator\File;

class Manifest implements File
{
    /**
     * @return string The file content
     */
    public function render()
    {
        $manifestDoc = new \DOMDocument('1.0', 'UTF-8');

        $root = $manifestDoc->createElement('manifest:manifest');
        $root->setAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');
        $manifestDoc->appendChild($root);

        $fileEntryRoot = $manifestDoc->createElement('manifest:file-entry');
        $fileEntryRoot->setAttribute('manifest:media-type', 'application/vnd.oasis.opendocument.text');
        $fileEntryRoot->setAttribute('manifest:full-path', '/');
        $root->appendChild($fileEntryRoot);

        $fileEntryContent = $manifestDoc->createElement('manifest:file-entry');
        $fileEntryContent->setAttribute('manifest:media-type', 'text/xml');
        $fileEntryContent->setAttribute('manifest:full-path', 'content.xml');
        $root->appendChild($fileEntryContent);

        $fileEntryStyles = $manifestDoc->createElement('manifest:file-entry');
        $fileEntryStyles->setAttribute('manifest:media-type', 'text/xml');
        $fileEntryStyles->setAttribute('manifest:full-path', 'styles.xml');
        $root->appendChild($fileEntryStyles);

        $fileEntryMeta = $manifestDoc->createElement('manifest:file-entry');
        $fileEntryMeta->setAttribute('manifest:media-type', 'text/xml');
        $fileEntryMeta->setAttribute('manifest:full-path', 'meta.xml');
        $root->appendChild($fileEntryMeta);

        return $manifestDoc->saveXML();
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'META-INF/manifest.xml';
    }
}
