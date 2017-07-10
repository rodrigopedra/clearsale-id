<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request\Fixtures;

use RodrigoPedra\ClearSaleID\Entity\Request\Phone;

class PhoneFixture
{
    public static function createPhone()
    {
        return Phone::create( Phone::COMERCIAL, '011', '3728-8788' );
    }
}
