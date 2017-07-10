<?php

namespace RodrigoPedra\ClearSaleID\Entity;

use XMLWriter;

interface XmlEntityInterface
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';

    public function toXML( XMLWriter $XMLWriter );
}
