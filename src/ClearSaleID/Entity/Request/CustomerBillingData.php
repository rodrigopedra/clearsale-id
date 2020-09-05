<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class CustomerBillingData extends AbstractCustomer implements XmlEntityInterface
{
    protected function getXMLWrapperElement(): string
    {
        return 'DadosCobranca';
    }
}
