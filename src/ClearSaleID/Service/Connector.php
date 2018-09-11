<?php

namespace RodrigoPedra\ClearSaleID\Service;

use RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment;
use SoapClient;

class Connector
{
    /** @var  \SoapClient */
    private $client;

    /** @var  \RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment */
    private $environment;

    /** @var  boolean */
    private $usesRegularEndpoint;

    /**
     * Connector constructor.
     *
     * @param  \RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment $environment
     */
    public function __construct( AbstractEnvironment $environment )
    {
        $this->environment         = $environment;
        $this->usesRegularEndpoint = true;
        $this->client              = null;
    }

    /**
     * @param  string $function
     * @param         $parameters
     * @param  bool   $usesRegularEndpoint
     *
     * @return mixed
     */
    public function doRequest( $function, $parameters, $usesRegularEndpoint = true )
    {
        $client = ( $usesRegularEndpoint === true )
            ? $this->useRegularEndpoint()->getClient()
            : $this->useExtendedEndpoint()->getClient();

        $arguments = [ $function => $parameters ];
        $options   = [ 'location' => $this->getEndpoint() ];

        $this->environment->log( 'Connector@doRequest: Request', compact( 'arguments', 'options' ) );

        $response = $client->__soapCall( $function, $arguments, $options );

        $this->environment->log( 'Connector@doRequest: Response', compact( 'response' ) );

        return $response;
    }

    /**
     * @return \SoapClient
     */
    private function getClient()
    {
        if (is_null( $this->client )) {
            $this->client = new SoapClient( $this->getEndpoint() . '?WSDL' );
        }

        return $this->client;
    }

    /**
     * @return string
     */
    private function getEndpoint()
    {
        if ($this->usesRegularEndpoint) {
            return $this->environment->getRegularEndpoint();
        }

        return $this->environment->getExtendedEndpoint();
    }

    /**
     * @return $this
     */
    private function useRegularEndpoint()
    {
        if ($this->usesRegularEndpoint === false) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = true;

        return $this;
    }

    /**
     * @return $this
     */
    private function useExtendedEndpoint()
    {
        if ($this->usesRegularEndpoint === true) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = false;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityCode()
    {
        return $this->environment->getEntityCode();
    }

    /**
     * @param  string $message
     * @param  array  $context
     */
    public function log( $message, array $context = [] )
    {
        $this->environment->log( $message, $context );
    }
}
