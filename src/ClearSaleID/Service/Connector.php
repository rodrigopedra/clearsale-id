<?php

namespace RodrigoPedra\ClearSaleID\Service;

use SoapClient;
use RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment;

class Connector
{
    /** @var  SoapClient */
    private $client;

    /** @var  AbstractEnvironment */
    private $environment;

    /** @var  boolean */
    private $usesRegularEndpoint;

    public function __construct( AbstractEnvironment $environment )
    {
        $this->environment         = $environment;
        $this->usesRegularEndpoint = true;
        $this->client              = null;
    }

    private function getEndpoint()
    {
        if ($this->usesRegularEndpoint) {
            return $this->environment->getRegularEndpoint();
        }

        return $this->environment->getExtendedEndpoint();
    }

    private function getClient()
    {
        if (is_null( $this->client )) {
            $this->client = new SoapClient( $this->getEndpoint() . '?WSDL' );
        }

        return $this->client;
    }

    private function useRegularEndpoint()
    {
        if ($this->usesRegularEndpoint === false) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = true;

        return $this;
    }

    private function useExtendedEndpoint()
    {
        if ($this->usesRegularEndpoint === true) {
            $this->client = null;
        }

        $this->usesRegularEndpoint = false;

        return $this;
    }

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

    public function getEntityCode()
    {
        return $this->environment->getEntityCode();
    }

    public function log( $message, array $context = [] )
    {
        $this->environment->log( $message, $context );
    }
}
