<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

use SplFileInfo;

/**
 * Class SofficeRenderer
 *
 * @package Juit\PhpOdt\OdtToPdfRenderer
 */
class SofficeRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @var string
     */
    private $libreOfficeBinaryPath;

    /**
     * SofficeRenderer constructor.
     *
     * @param SplFileInfo|null $backgroundPdfPath
     * @param SplFileInfo|null $stampPdfPath
     * @param string|null      $libreOfficeBinaryPath
     */
    public function __construct(
        SplFileInfo $backgroundPdfPath = null,
        SplFileInfo $stampPdfPath = null,
        $libreOfficeBinaryPath = null
    ) {
        parent::__construct($backgroundPdfPath, $stampPdfPath);

        if (null === $libreOfficeBinaryPath) {
            $libreOfficeBinaryPath = '/usr/bin/soffice';
        }
        $this->libreOfficeBinaryPath = $libreOfficeBinaryPath;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @param string       $homeFolder
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile, $homeFolder = null)
    {
        if (null === $homeFolder) {
            $homeFolder = 'file:///var/www/web/libreoffice';
        }

        return $this->libreOfficeBinaryPath .
            ' -env:UserInstallation=' . $homeFolder . ' --headless --convert-to pdf ' .
            $odtFile->getPathname() .
            ' --outdir ' . $odtFile->getPath();
    }
}
