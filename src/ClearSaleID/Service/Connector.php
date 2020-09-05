<?php

namespace RodrigoPedra\ClearSaleID\Service;

use RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment;

class Connector
{
    /** @var  \SoapClient */
    private $client;

    /** @var  \RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment */
    private $environment;

    /** @var  boolean */
    private $usesRegularEndpoint;

    public function __construct(AbstractEnvironment $environment)
    {
        $this->environment = $environment;
        $this->usesRegularEndpoint = true;
        $this->client = null;
    }

    /**
     * @param  string  $function
     * @param         $parameters
     * @param  bool  $usesRegularEndpoint
     * @return mixed
     * @throws \SoapFault
     */
    public function doRequest(string $function, $parameters, bool $usesRegularEndpoint = true)
    {
        $client = $usesRegularEndpoint
            ? $this->useRegularEndpoint()->getClient()
            : $this->useExtendedEndpoint()->getClient();

        $arguments = [$function => $parameters];
        $options = ['location' => $this->getEndpoint()];

        $this->environment->log('Connector@doRequest: Request', \compact('arguments', 'options'));

        $response = $client->__soapCall($function, $arguments, $options);

        $this->environment->log('Connector@doRequest: Response', \compact('response'));

        return $response;
    }

    /**
     * @return \SoapClient
     * @throws \SoapFault
     */
    private function getClient(): \SoapClient
    {
        if (\is_null($this->client)) {
            $this->client = new \SoapClient($this->getEndpoint() . '?WSDL');
        }

        return $this->client;
    }

    private function getEndpoint(): string
    {
        if ($this->usesRegularEndpoint) {
            return $this->environment->getRegularEndpoint();
        }

        return $this->environment->getExtendedEndpoint();
    }

    private function useRegularEndpoint(): self
    {
        if (! $this->usesRegularEndpoint) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = true;

        return $this;
    }

    private function useExtendedEndpoint(): self
    {
        if ($this->usesRegularEndpoint) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = false;

        return $this;
    }

    public function getEntityCode(): string
    {
        return $this->environment->getEntityCode();
    }

    public function log(string $message, array $context = []): void
    {
        $this->environment->log($message, $context);
    }
}
