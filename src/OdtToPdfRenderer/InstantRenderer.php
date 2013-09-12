<?php

namespace OdtToPdfRenderer;

use Adticket\Elvis\CommunicationBundle\FormLetter\Cache;

class InstantRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @var string
     */
    private $libreOfficeBinaryPath;

    public function __construct(Cache $cache, $libreOfficeBinaryPath = null)
    {
        parent::__construct($cache);

        if (null === $libreOfficeBinaryPath) {
            $libreOfficeBinaryPath = '/usr/bin/soffice';
        }
        $this->libreOfficeBinaryPath = $libreOfficeBinaryPath;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile)
    {
        return
            "{$this->libreOfficeBinaryPath} "
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
