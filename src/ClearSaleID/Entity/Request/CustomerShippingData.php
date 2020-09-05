<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class CustomerShippingData extends AbstractCustomer implements XmlEntityInterface
{
    protected function getXMLWrapperElement(): string
    {
        return 'DadosEntrega';
    }
}
