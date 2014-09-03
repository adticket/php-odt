<?php

namespace OdtCreator\Test\Unit\ODTCreator\Document;

use Juit\PhpOdt\OdtCreator\Document\MetaFile;

class MetaMock extends MetaFile
{
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }
}
