<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

class ServiceRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @var \SplFileInfo
     */
    private $unoconvBinaryPath;

    /**
     * @var string
     */
    private $daemonHost;

    /**
     * @var string
     */
    private $daemonPort;

    public function __construct(
        \SplFileInfo $unoconvBinaryPath = null,
        $daemonHost = '127.0.0.1',
        $daemonPort = '2200'
    )
    {
        if (null === $unoconvBinaryPath) {
            $unoconvBinaryPath = new \SplFileInfo('/usr/bin/unoconv');
        }
        $this->unoconvBinaryPath = $unoconvBinaryPath;
        $this->daemonHost = $daemonHost;
        $this->daemonPort = $daemonPort;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile)
    {
        $unoconvBinaryPath = $this->unoconvBinaryPath->getPathname();
        $daemonHost = $this->daemonHost;
        $daemonPort = $this->daemonPort;

        return
            "$unoconvBinaryPath "
            . "--connection 'socket,host=$daemonHost,port=$daemonPort,tcpNoDelay=1;urp;StarOffice.ComponentContext' "
            . "--format=pdf "
            . "--outputpath={$odtFile->getPath()} "
            . "{$odtFile->getPathname()} "
            ;
    }

    /**
     * @return array
     */
    protected function getOutputWhiteListRegexes()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function getErrorWhiteListRegexes()
    {
        return array(
            '/^Warning: -.* is deprecated\./'
        );
    }
}
