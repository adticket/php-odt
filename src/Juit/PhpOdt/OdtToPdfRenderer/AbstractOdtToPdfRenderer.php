<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

use Symfony\Component\Process\Process;

abstract class AbstractOdtToPdfRenderer
{
    /**
     * @param \SplFileInfo $odtFileInfo The input file
     * @param \SplFileInfo $pdfFileInfo The output file
     * @throws \RuntimeException
     */
    public function render(\SplFileInfo $odtFileInfo, \SplFileInfo $pdfFileInfo)
    {
        $shellCommand = $this->createShellCommand($odtFileInfo);

        $process = new Process($shellCommand);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "Unexpected result ({$process->getExitCode()}) from LibreOffice: "
                . $process->getErrorOutput()
            );
        }

        // TODO: This is just a dev experiment
//        $out = new \SplFileInfo($pdfFileInfo->getPath() . '/with_background.pdf');
//        $cmd = "/usr/bin/pdftk {$pdfFileInfo->getPathname()}"
//            . " multibackground /var/www/app/doc/Briefbogen.pdf"
//            . " output {$out->getPathname()}";
//        exec($cmd);
//        unlink($pdfFileInfo->getPathname());
//        rename($out->getPathname(), $pdfFileInfo->getPathname());
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    abstract protected function createShellCommand(\SplFileInfo $odtFile);
}
