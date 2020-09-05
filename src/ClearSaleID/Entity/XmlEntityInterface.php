<?php

namespace RodrigoPedra\ClearSaleID\Entity;

interface XmlEntityInterface
{
    public const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';

    public function toXML(\XMLWriter $XMLWriter);
}
