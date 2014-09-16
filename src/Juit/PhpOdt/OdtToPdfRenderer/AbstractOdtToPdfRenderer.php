<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

use SplFileInfo;
use Symfony\Component\Process\Process;

abstract class AbstractOdtToPdfRenderer
{
    /**
     * @var SplFileInfo|null
     */
    private $backgroundPdfPath;

    public function __construct(SplFileInfo $backgroundPdfPath = null)
    {
        $this->backgroundPdfPath = $backgroundPdfPath;
    }

    /**
     * @param SplFileInfo $odtFileInfo The input file
     * @return SplFileInfo
     * @throws \RuntimeException
     */
    public function render(SplFileInfo $odtFileInfo)
    {
        $pdfOutput = $this->renderOdtToPdf($odtFileInfo);
        $this->applyBackground($pdfOutput);

        return $pdfOutput;
    }

    /**
     * @param SplFileInfo $odtFile
     * @return SplFileInfo
     */
    private function renderOdtToPdf(SplFileInfo $odtFile)
    {
        $shellCommand = $this->createShellCommand($odtFile);

        $process = new Process($shellCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "Unexpected result ({$process->getExitCode()}) from LibreOffice: "
                . $process->getErrorOutput()
            );
        }

        $pdfPath = $odtFile->getPath() . '/' . $odtFile->getBasename('.odt') . '.pdf';

        return new SplFileInfo($pdfPath);
    }

    /**
     * @param SplFileInfo $odtFile
     * @return string
     */
    abstract protected function createShellCommand(SplFileInfo $odtFile);

    /**
     * @param SplFileInfo $plainPdf
     */
    private function applyBackground(SplFileInfo $plainPdf)
    {
        if (null === $this->backgroundPdfPath) {
            return;
        }

        $tmpFilePath = $plainPdf->getPath() . '/' . $plainPdf->getBasename('.pdf') . '.with_background.pdf';
        $tmpFile = new \SplFileInfo($tmpFilePath);

        $shellCommand = "/usr/bin/pdftk {$plainPdf}"
            . " multibackground {$this->backgroundPdfPath}"
            . " output {$tmpFile}";

        $process = new Process($shellCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "Unexpected result ({$process->getExitCode()}) from pdftk: "
                . $process->getErrorOutput()
            );
        }

        unlink($plainPdf);
        rename($tmpFile, $plainPdf);
    }
}
