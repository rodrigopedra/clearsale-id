<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

use Exception;
use RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException;
use RodrigoPedra\ClearSaleID\Exception\UpdateOrderStatusException;

class UpdateOrderStatus
{
    const STATUS_CODE_OK = 'OK';

    /** @var  string */
    private $statusCode;

    /** @var  string */
    private $message;

    /**
     * UpdateOrderStatus constructor.
     *
     * @param  string $xml
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     * @throws \RodrigoPedra\ClearSaleID\Exception\UpdateOrderStatusException
     */
    public function __construct( $xml )
    {
        try {
            // FIX PHP Warning: Parser error : Document labelled UTF-16 but has UTF-8 content
            $xml = preg_replace( '/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml );

            // Convert string to SimpleXMLElement
            $object = simplexml_load_string( $xml );

            // Convert SimpleXMLElement to stdClass
            $updateOrderStatusObject = json_decode( json_encode( $object ) );

            if (is_null( $updateOrderStatusObject )) {
                throw new UnexpectedErrorException( sprintf( 'Invalid response from webservice (%s)', $xml ), 0 );
            }

            $this->setStatusCode( $updateOrderStatusObject->StatusCode );
            $this->setMessage( $updateOrderStatusObject->Message );
        } catch ( Exception $ex ) {
            throw new UnexpectedErrorException( sprintf( 'Invalid response from webservice (%s)', $xml ), 0, $ex );
        }

        $this->validateStatusCode();
    }

    /**
     * @param  int $statusCode
     *
     * @return $this
     */
    private function setStatusCode( $statusCode )
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param  string $message
     *
     * @return $this
     */
    private function setMessage( $message )
    {
        $this->message = trim( $message );

        return $this;
    }

    /**
     * @throws \RodrigoPedra\ClearSaleID\Exception\UpdateOrderStatusException
     */
    private function validateStatusCode()
    {
        if ($this->getStatusCode() === self::STATUS_CODE_OK) {
            return;
        }

        throw new UpdateOrderStatusException( sprintf( 'Update order status failed (%s)', $this->getStatusCode() ) );
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() === self::STATUS_CODE_OK;
    }
}
