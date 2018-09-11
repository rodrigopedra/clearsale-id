<?php

namespace RodrigoPedra\ClearSaleID\Environment;

use Psr\Log\LoggerInterface;

abstract class AbstractEnvironment
{
    /** @var  string */
    protected $regularEndpoint;

    /** @var  string */
    protected $extendedEnpoint;

    /** @var  string */
    private $entityCode;

    /** @var  bool */
    private $debug;

    /** @var  \Psr\Log\LoggerInterface|null */
    private $logger;

    /**
     * AbstractEnvironment constructor.
     *
     * @param  string                        $entityCode
     * @param  \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct( $entityCode, LoggerInterface $logger = null )
    {
        $this->entityCode = $entityCode;
        $this->logger     = $logger;
        $this->debug      = false;
    }

    /**
     * @return string
     */
    public function getEntityCode()
    {
        return $this->entityCode;
    }

    /**
     * @return string
     */
    public function getRegularEndpoint()
    {
        return $this->regularEndpoint;
    }

    /**
     * @return string
     */
    public function getExtendedEndpoint()
    {
        return $this->extendedEnpoint;
    }

    /**
     * @param  string $message
     * @param  array  $context
     */
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

    /**
     * @return bool
     */
    public function isDebug()
    {
        return (bool)$this->debug;
    }

    /**
     * @param  bool $debug
     *
     * @return $this
     */
    public function setDebug( $debug = true )
    {
        $this->debug = $debug;

        return $this;
    }
}
