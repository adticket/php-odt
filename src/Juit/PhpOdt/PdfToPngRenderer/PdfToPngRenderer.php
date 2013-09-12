<?php

namespace Juit\PhpOdt\PdfToPngRenderer;

use Juit\ShellCommandExecutor\Result;
use Juit\ShellCommandExecutor\ShellCommandExecutor;

class PdfToPngRenderer
{
    /**
     * @var string
     */
    private $ghostscriptBinaryPath;

    public function __construct($ghostscriptBinaryPath = null)
    {
        if (null === $ghostscriptBinaryPath) {
            $ghostscriptBinaryPath = '/usr/bin/gs';
        }
        $this->ghostscriptBinaryPath = $ghostscriptBinaryPath;
    }

    /**
     * @param \SplFileInfo $pdfFileInfo
     * @param int|null $firstPage
     * @param int|null $lastPage
     * @return \SplFileInfo[]
     */
    public function render(\SplFileInfo $pdfFileInfo, $firstPage = null, $lastPage = null)
    {
        $outputFileNamePattern = $this->createOutputFileNamePattern($pdfFileInfo);
        $shellCommand = $this->createShellCommand($pdfFileInfo, $outputFileNamePattern, $firstPage, $lastPage);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        return $this->extractOutputFileInfos($result, $outputFileNamePattern);
    }

    /**
     * @param \SplFileInfo $pdfFile
     * @param int $pageNumber
     * @return \SplFileInfo
     */
    public function renderSinglePage(\SplFileInfo $pdfFile, $pageNumber = 1)
    {
        $files = $this->render($pdfFile, $pageNumber, $pageNumber);

        return $files[0];
    }

    /**
     * @param \SplFileInfo $pdfFile
     * @param string $outputFileNamePattern
     * @param int|null $firstPage
     * @param int|null $lastPage
     * @return string
     */
    private function createShellCommand(\SplFileInfo $pdfFile, $outputFileNamePattern, $firstPage, $lastPage)
    {
        $shellCommand =
            "{$this->ghostscriptBinaryPath} "
            . "-dSAFER -dBATCH -dNOPAUSE "
            . "-sDEVICE=png16m "
            . "-dTextAlphaBits=4 "
            . "-dGraphicsAlphaBits=4 "
            . "-dMaxBitmap=500000000 "
        ;

        if (null !== $firstPage) {
            $shellCommand .= "-dFirstPage=$firstPage ";
        }
        if (null !== $lastPage) {
            $shellCommand .= "-dLastPage=$lastPage ";
        }

        $shellCommand .=
            "-r300 "
            . "-sOutputFile=\"{$outputFileNamePattern}\" "
            . "{$pdfFile->getRealPath()} "
        ;

        return $shellCommand;
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
        // TODO: This is currently not used
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

        $pageNumber = 1;
        foreach ($result->getOutput() as $line) {
            $matches = array();
            // TODO: GS does NOT use the actual page number in its filenames. It starts with 1 for the first rendered page.
            if (!preg_match('/^Page (?P<page_number>\d+)$/', $line, $matches)) {
                continue;
            }

//            $pageNumber = $matches['page_number'];
            $filePath = sprintf($outputFileNamePattern, $pageNumber);
            $pageNumber++;

            $fileInfos[] = new \SplFileInfo($filePath);
        }

        return $fileInfos;
    }
}
