<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

class Production extends AbstractEnvironment
{
    public function __construct(string $entityCode, ?LoggerInterface $logger = null)
    {
        parent::__construct(
            'https://integracao.clearsale.com.br/service.asmx',
            'https://integracao.clearsale.com.br/ExtendedService.asmx',
            $entityCode,
            $logger
        );
    }
}
