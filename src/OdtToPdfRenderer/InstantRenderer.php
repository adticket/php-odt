<?php

namespace OdtToPdfRenderer;

class InstantRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @var \SplFileInfo
     */
    private $libreOfficeBinary;

    public function __construct(\SplFileInfo $libreOfficeBinary = null)
    {
        if (null === $libreOfficeBinary) {
            $libreOfficeBinary = new \SplFileInfo('/usr/bin/soffice');
        }
        $this->libreOfficeBinary = $libreOfficeBinary;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile)
    {
        return
            "{$this->libreOfficeBinary->getPathname()} "
            . "--headless --convert-to pdf "
            . "{$odtFile->getPathname()} "
            . "--outdir {$odtFile->getPath()}";
    }

    /**
     * @return array
     */
    protected function getOutputWhiteListRegexes()
    {
        return array(
            '/^convert .* using writer_pdf_Export$/',
            '/^Overwriting: /'
        );
    }

    /**
     * @return array
     */
    protected function getErrorWhiteListRegexes()
    {
        return array();
    }
}
