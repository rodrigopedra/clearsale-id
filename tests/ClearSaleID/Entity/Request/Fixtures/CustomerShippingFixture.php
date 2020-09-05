<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures;

use RodrigoPedra\ClearSaleID\Entity\Request\AbstractCustomer;
use RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData;

class CustomerShippingFixture
{
    public static function createCustomerShippingData()
    {
        $id = '1';
        $legalDocument = '63165236372';
        $name = 'Fulano da Silva';
        $address = AddressFixture::createAddress();
        $phone = PhoneFixture::createPhone();

        return CustomerShippingData::create(
            $id,
            AbstractCustomer::TYPE_PESSOA_FISICA,
            $legalDocument,
            $name,
            $address,
            $phone
        );
    }
}
