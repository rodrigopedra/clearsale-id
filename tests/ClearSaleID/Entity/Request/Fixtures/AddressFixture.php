<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures;

use RodrigoPedra\ClearSaleID\Entity\Request\Address;

class AddressFixture
{
    public static function createAddress(): Address
    {
        $street = 'Rua José de Oliveira Coutinho';
        $number = 151;
        $county = 'Barra Funda';
        $country = 'Brasil';
        $city = 'São Paulo';
        $state = 'SP';
        $zip = '01144020';
        $complement = 'apto. 500';

        return Address::create($street, $number, $county, $country, $city, $state, $zip, $complement);
    }
}
