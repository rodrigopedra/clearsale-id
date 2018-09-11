<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

class Production extends AbstractEnvironment
{
    /**
     * Production constructor.
     *
     * @param  string                        $entityCode
     * @param  \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct( $entityCode, LoggerInterface $logger = null )
    {
        parent::__construct( $entityCode, $logger );

        $this->regularEndpoint = 'https://integracao.clearsale.com.br/service.asmx';
        $this->extendedEnpoint = 'https://integracao.clearsale.com.br/ExtendedService.asmx';
    }
}
