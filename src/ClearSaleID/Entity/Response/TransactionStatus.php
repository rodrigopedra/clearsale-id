<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

use Exception;
use RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException;

class TransactionStatus
{
    private $transactionId;
    private $statusCode;
    private $message;

    public function __construct( $xml )
    {
        try {
            // FIX PHP Warning: Parser error : Document labelled UTF-16 but has UTF-8 content
            $xml = preg_replace( '/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml );

            // Convert string to SimpleXMLElement
            $object = simplexml_load_string( $xml );

            // Convert SimpleXMLElement to stdClass
            $transactionStatusObject = json_decode( json_encode( $object ) );

            if (is_null( $transactionStatusObject )) {
                throw new UnexpectedErrorException( sprintf( 'Invalid response from webservice (%s)', $xml ), 0 );
            }

            if (isset( $transactionStatusObject->TransactionID )) {
                $this->setTransactionId( $transactionStatusObject->TransactionID );
            }

            $this->setStatusCode( $transactionStatusObject->StatusCode );
            $this->setMessage( $transactionStatusObject->Message );
        } catch ( Exception $ex ) {
            throw new UnexpectedErrorException( sprintf( 'Invalid response from webservice (%s)', $xml ), 0, $ex );
        }
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getStatusCode()
    {
        return intval( $this->statusCode );
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function setTransactionId( $transactionId )
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    private function setStatusCode( $statusCode )
    {
        if (preg_match( '/\d+/', $statusCode ) !== 1) {
            throw new UnexpectedErrorException( sprintf( 'Invalid Status Code (%s)', $statusCode ) );
        }

        $this->statusCode = intval( $statusCode );

        return $this;
    }

    private function setMessage( $message )
    {
        $this->message = trim( $message );

        return $this;
    }
}
