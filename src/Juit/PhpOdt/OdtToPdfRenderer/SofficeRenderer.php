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
     * @var string
     */
    private $homeFolder;

    /**
     * SofficeRenderer constructor.
     *
     * @param SplFileInfo|null $backgroundPdfPath
     * @param SplFileInfo|null $stampPdfPath
     * @param string|null      $libreOfficeBinaryPath
     * @param string|null      $homeFolder
     */
    public function __construct(
        SplFileInfo $backgroundPdfPath = null,
        SplFileInfo $stampPdfPath = null,
        $libreOfficeBinaryPath = null,
        $homeFolder = null
    ) {
        parent::__construct($backgroundPdfPath, $stampPdfPath);

        if (null === $libreOfficeBinaryPath) {
            $libreOfficeBinaryPath = '/usr/bin/soffice';
        }

        if (null === $homeFolder) {
            $homeFolder = 'file:///var/www/web/libreoffice';
        }

        $this->libreOfficeBinaryPath = $libreOfficeBinaryPath;
        $this->homeFolder = $homeFolder;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile)
    {
        return $this->libreOfficeBinaryPath .
            ' -env:UserInstallation=' . $this->homeFolder . ' --headless --convert-to pdf ' .
            $odtFile->getPathname() .
            ' --outdir ' . $odtFile->getPath();
    }
}
