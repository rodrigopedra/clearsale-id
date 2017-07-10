<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request\Fixtures;

use RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint;

class FingerPrintFixture
{
    public static function createFingerPrint()
    {
        return new FingerPrint( 'session-id-1234' );
    }
}
