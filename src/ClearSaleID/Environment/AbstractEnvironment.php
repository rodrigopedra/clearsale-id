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

    public function __construct(
        string $regularEndpoint,
        string $extendedEnpoint,
        string $entityCode,
        ?LoggerInterface $logger
    ) {
        $this->regularEndpoint = $regularEndpoint;
        $this->extendedEnpoint = $extendedEnpoint;
        $this->entityCode = $entityCode;
        $this->logger = $logger;
        $this->debug = false;
    }

    public function getEntityCode(): string
    {
        return $this->entityCode;
    }

    public function getRegularEndpoint(): string
    {
        return $this->regularEndpoint;
    }

    public function getExtendedEndpoint(): string
    {
        return $this->extendedEnpoint;
    }

    public function log(string $message, array $context = []): void
    {
        if (! $this->isDebug()) {
            return;
        }

        if ($this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function setDebug(bool $debug = true): self
    {
        $this->debug = $debug;

        return $this;
    }
}
