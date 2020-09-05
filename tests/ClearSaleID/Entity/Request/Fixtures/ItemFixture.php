<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures;

use RodrigoPedra\ClearSaleID\Entity\Request\Item;

class ItemFixture
{
    public static function createItem()
    {
        return Item::create(1, 'Adaptador USB', 10.0, 1);
    }
}
