<?php

namespace ODTCreator\Test\Unit\ODTCreator\Document;

use ODTCreator\Document\Meta;

class MetaMock extends Meta
{
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }
}
