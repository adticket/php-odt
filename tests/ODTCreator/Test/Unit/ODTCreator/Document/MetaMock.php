<?php

namespace OdtCreator\Test\Unit\ODTCreator\Document;

use OdtCreator\Document\Meta;

class MetaMock extends Meta
{
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }
}
