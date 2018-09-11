<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

use Exception;
use RodrigoPedra\ClearSaleID\Exception\EntityCodeNotFoundException;
use RodrigoPedra\ClearSaleID\Exception\OrderAlreadySentException;
use RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException;
use RodrigoPedra\ClearSaleID\Exception\XmlTransformException;
use RodrigoPedra\ClearSaleID\Exception\XmlValidationException;

class PackageStatus
{
    const STATUS_CODE_SUCCESS               = 0;
    const STATUS_CODE_ENTITY_CODE_NOT_FOUND = 1;
    const STATUS_CODE_INVALID_XML           = 2;
    const STATUS_CODE_XML_TRANSFORM_ERROR   = 3;
    const STATUS_CODE_INTERNAL_ERROR_4      = 4;
    const STATUS_CODE_ORDER_ALREADY_SENT    = 5;
    const STATUS_CODE_INTERNAL_ERROR_6      = 6;
    const STATUS_CODE_INTERNAL_ERROR_7      = 7;

    private static $statusCodesList = [
        self::STATUS_CODE_SUCCESS,
        self::STATUS_CODE_ENTITY_CODE_NOT_FOUND,
        self::STATUS_CODE_INVALID_XML,
        self::STATUS_CODE_XML_TRANSFORM_ERROR,
        self::STATUS_CODE_INTERNAL_ERROR_4,
        self::STATUS_CODE_ORDER_ALREADY_SENT,
        self::STATUS_CODE_INTERNAL_ERROR_6,
        self::STATUS_CODE_INTERNAL_ERROR_7,
    ];

    private static $statusCodeExceptions = [
        self::STATUS_CODE_ENTITY_CODE_NOT_FOUND => EntityCodeNotFoundException::class,
        self::STATUS_CODE_INVALID_XML           => XmlValidationException::class,
        self::STATUS_CODE_XML_TRANSFORM_ERROR   => XmlTransformException::class,
        self::STATUS_CODE_INTERNAL_ERROR_4      => UnexpectedErrorException::class,
        self::STATUS_CODE_ORDER_ALREADY_SENT    => OrderAlreadySentException::class,
        self::STATUS_CODE_INTERNAL_ERROR_6      => UnexpectedErrorException::class,
        self::STATUS_CODE_INTERNAL_ERROR_7      => UnexpectedErrorException::class,
    ];

    /** @var  string */
    private $transactionId;

    /** @var  int */
    private $statusCode;

    /** @var  string */
    private $message;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Response\Order */
    private $order;

    /**
     * PackageStatus constructor.
     *
     * @param  string $xml
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     * @throws \Exception
     */
    public function __construct( $xml )
    {
        try {
            // FIX PHP Warning: Parser error : Document labelled UTF-16 but has UTF-8 content
            $xml = preg_replace( '/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml );

            // Convert string to SimpleXMLElement
            $object = simplexml_load_string( $xml );

            // Convert SimpleXMLElement to stdClass
            $object = json_decode( json_encode( $object ) );

            $packageStatusObject = isset( $object->PackageStatus ) ? $object->PackageStatus : null;

            if (is_null( $packageStatusObject )) {
                throw new UnexpectedErrorException( 'Invalid response from webservice', 0 );
            }

            $this->setTransactionId( $packageStatusObject->TransactionID );
            $this->setStatusCode( $packageStatusObject->StatusCode );
            $this->setMessage( $packageStatusObject->Message );

            if (isset( $packageStatusObject->Pedidos )) {
                $this->setOrder( $packageStatusObject->Pedidos );
            }
        } catch ( Exception $ex ) {
            throw new UnexpectedErrorException( 'Invalid response from webservice', 0, $ex );
        }

        $this->validateStatusCode();
    }

    /**
     * @param  string $transactionId
     *
     * @return $this
     */
    private function setTransactionId( $transactionId )
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @param  int $statusCode
     *
     * @return $this
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     */
    private function setStatusCode( $statusCode )
    {
        $statusCode = intval( $statusCode );

        if (!in_array( $statusCode, self::$statusCodesList )) {
            throw new UnexpectedErrorException( sprintf( 'Invalid Status Code (%s)', $statusCode ) );
        }

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
     * @param  $orderObject
     *
     * @return $this
     */
    private function setOrder( $orderObject )
    {
        $this->order = new Order(
            $orderObject->Pedido->ID,
            $orderObject->Pedido->Score,
            $orderObject->Pedido->Status,
            $orderObject->Pedido->URLQuestionario
        );

        return $this;
    }

    /**
     * @throws  \Exception
     */
    private function validateStatusCode()
    {
        if (self::STATUS_CODE_SUCCESS === $this->getStatusCode()) {
            return;
        }

        if (self::STATUS_CODE_ENTITY_CODE_NOT_FOUND === $this->getStatusCode()) {
            $this->setMessage( 'entity code not found' );
        }

        $exceptionClass = static::$statusCodeExceptions[ $this->getStatusCode() ];

        throw new $exceptionClass( $this->getMessage(), $this->getStatusCode() );
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return intval( $this->statusCode );
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() === self::STATUS_CODE_SUCCESS;
    }
}
