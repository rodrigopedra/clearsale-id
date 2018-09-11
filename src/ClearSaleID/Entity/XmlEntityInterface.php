<?php

namespace RodrigoPedra\ClearSaleID\Entity;

use XMLWriter;

interface XmlEntityInterface
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * @param  \XMLWriter $XMLWriter
     */
    public function toXML( XMLWriter $XMLWriter );
}
