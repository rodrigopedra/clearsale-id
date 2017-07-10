<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

class Sandbox extends AbstractEnvironment
{
    public function __construct( $entityCode, LoggerInterface $logger = null )
    {
        parent::__construct( $entityCode, $logger );

        $this->regularEndpoint = 'https://homologacao.clearsale.com.br/integracaov2/service.asmx';
        $this->extendedEnpoint = 'https://homologacao.clearsale.com.br/integracaov2/ExtendedService.asmx';
    }
}
