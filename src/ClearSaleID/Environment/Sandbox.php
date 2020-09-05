<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

class Sandbox extends AbstractEnvironment
{
    public function __construct(string $entityCode, ?LoggerInterface $logger = null)
    {
        parent::__construct(
            'https://homologacao.clearsale.com.br/integracaov2/service.asmx',
            'https://homologacao.clearsale.com.br/integracaov2/ExtendedService.asmx',
            $entityCode,
            $logger
        );
    }
}
