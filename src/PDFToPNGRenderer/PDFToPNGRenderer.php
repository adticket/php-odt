<?php

namespace PDFToPNGRenderer;

use ShellCommandExecutor\Result;
use ShellCommandExecutor\ShellCommandExecutor;

class PDFToPNGRenderer
{
    /**
     * @var \SplFileInfo
     */
    private $ghostscriptBinary;

    public function __construct(\SplFileInfo $ghostscriptBinary = null)
    {
        if (null === $ghostscriptBinary) {
            $ghostscriptBinary = new \SplFileInfo('/usr/bin/gs');
        }
        $this->ghostscriptBinary = $ghostscriptBinary;
    }

    /**
     * @param \SplFileInfo $pdfFile
     * @return \SplFileInfo[]
     */
    public function render(\SplFileInfo $pdfFile)
    {
        $outputFileNamePattern = $this->createOutputFileNamePattern($pdfFile);
        $shellCommand = $this->createShellCommand($pdfFile, $outputFileNamePattern);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        $this->assertIsValid($result);

        return $this->extractOutputFileInfos($result, $outputFileNamePattern);
    }

    /**
     * @param \SplFileInfo $pdfFile
     * @param string $outputFileNamePattern
     * @return string
     */
    private function createShellCommand(\SplFileInfo $pdfFile, $outputFileNamePattern)
    {
        return
            "{$this->ghostscriptBinary->getPathname()} "
            . "-dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m "
            . "-sOutputFile=\"{$outputFileNamePattern}\" "
            . "-dTextAlphaBits=4 -dMaxBitmap=500000000 "
            . "{$pdfFile->getRealPath()} "
            . "-r300";
    }

    /**
     * @param \SplFileInfo $pdfFile
     * @return string
     */
    private function createOutputFileNamePattern(\SplFileInfo $pdfFile)
    {
        $fileName = $pdfFile->getBasename();
        $fileNameWithoutExtension = substr($fileName, 0, strrpos($fileName, '.'));

        return $pdfFile->getPath() . '/' . $fileNameWithoutExtension . '-%d.png';
    }

    /**
     * @param Result $result
     * @throws \RuntimeException
     */
    private function assertIsValid(Result $result)
    {
        $isReturnValueError = ($result->getReturnVar() !== 0);
        $hasErrorOutput = (count($result->getErrors()) > 0);

        if ($isReturnValueError || $hasErrorOutput) {
            throw new \RuntimeException("Unexpected result from Ghostscript");
        }
    }

    /**
     * @param Result $result
     * @param string $outputFileNamePattern
     * @return \SplFileInfo[]
     */
    private function extractOutputFileInfos(Result $result, $outputFileNamePattern)
    {
        $fileInfos = array();

        foreach ($result->getOutput() as $line) {
            $matches = array();
            if (!preg_match('/^Page (?P<page_number>\d+)$/', $line, $matches)) {
                continue;
            }

            $pageNumber = $matches['page_number'];
            $filePath = sprintf($outputFileNamePattern, $pageNumber);

            $fileInfos[] = new \SplFileInfo($filePath);
        }

        return $fileInfos;
    }
}
