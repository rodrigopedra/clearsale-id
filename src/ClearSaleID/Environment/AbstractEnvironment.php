<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

abstract class AbstractEnvironment
{
    /** @var string */
    protected $regularEndpoint;

    /** @var string */
    protected $extendedEnpoint;

    /** @var string */
    private $entityCode;

    /** @var bool */
    private $debug;

    /** @var LoggerInterface|null */
    private $logger;

    public function __construct( $entityCode, LoggerInterface $logger = null )
    {
        $this->entityCode = $entityCode;
        $this->logger     = $logger;
        $this->debug      = false;
    }

    public function getEntityCode()
    {
        return $this->entityCode;
    }

    public function getRegularEndpoint()
    {
        return $this->regularEndpoint;
    }

    public function getExtendedEndpoint()
    {
        return $this->extendedEnpoint;
    }

    public function isDebug()
    {
        return (bool)$this->debug;
    }

    public function setDebug( $debug = true )
    {
        $this->debug = $debug;

        return $this;
    }

    public function log( $message, array $context = [] )
    {
        if (!$this->isDebug()) {
            return;
        }

        if (!$this->logger) {
            return;
        }

        $this->logger->debug( $message, $context );
    }
}
