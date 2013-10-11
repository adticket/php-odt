<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

class InstantRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @var string
     */
    private $libreOfficeBinaryPath;

    public function __construct($libreOfficeBinaryPath = null)
    {
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
}
