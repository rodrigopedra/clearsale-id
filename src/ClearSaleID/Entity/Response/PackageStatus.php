<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

use RodrigoPedra\ClearSaleID\Exception\EntityCodeNotFoundException;
use RodrigoPedra\ClearSaleID\Exception\OrderAlreadySentException;
use RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException;
use RodrigoPedra\ClearSaleID\Exception\XmlTransformException;
use RodrigoPedra\ClearSaleID\Exception\XmlValidationException;

class PackageStatus
{
    public const STATUS_CODE_SUCCESS = 0;
    public const STATUS_CODE_ENTITY_CODE_NOT_FOUND = 1;
    public const STATUS_CODE_INVALID_XML = 2;
    public const STATUS_CODE_XML_TRANSFORM_ERROR = 3;
    public const STATUS_CODE_INTERNAL_ERROR_4 = 4;
    public const STATUS_CODE_ORDER_ALREADY_SENT = 5;
    public const STATUS_CODE_INTERNAL_ERROR_6 = 6;
    public const STATUS_CODE_INTERNAL_ERROR_7 = 7;

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
        self::STATUS_CODE_INVALID_XML => XmlValidationException::class,
        self::STATUS_CODE_XML_TRANSFORM_ERROR => XmlTransformException::class,
        self::STATUS_CODE_INTERNAL_ERROR_4 => UnexpectedErrorException::class,
        self::STATUS_CODE_ORDER_ALREADY_SENT => OrderAlreadySentException::class,
        self::STATUS_CODE_INTERNAL_ERROR_6 => UnexpectedErrorException::class,
        self::STATUS_CODE_INTERNAL_ERROR_7 => UnexpectedErrorException::class,
    ];

    /** @var  string */
    private $transactionId;

    /** @var  int */
    private $statusCode;

    /** @var  string */
    private $message;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Response\Order|null */
    private $order = null;

    public function __construct(string $xml)
    {
        try {
            // FIX PHP Warning: Parser error : Document labelled UTF-16 but has UTF-8 content
            $xml = \preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

            $object = \simplexml_load_string($xml);
            $object = \json_decode(\json_encode($object));

            $packageStatusObject = isset($object->PackageStatus) ? $object->PackageStatus : null;

            if (\is_null($packageStatusObject)) {
                throw new UnexpectedErrorException('Invalid response from webservice', 0);
            }

            $this->setTransactionId($packageStatusObject->TransactionID);
            $this->setStatusCode($packageStatusObject->StatusCode);
            $this->setMessage($packageStatusObject->Message);

            if (isset($packageStatusObject->Pedidos)) {
                $this->setOrder($packageStatusObject->Pedidos);
            }
        } catch (\Throwable $exception) {
            throw new UnexpectedErrorException('Invalid response from webservice', 0, $exception);
        }

        $this->guardStatusCode();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === self::STATUS_CODE_SUCCESS;
    }

    private function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    private function setStatusCode(int $statusCode): self
    {
        if (! \in_array($statusCode, self::$statusCodesList)) {
            throw new UnexpectedErrorException(\sprintf('Invalid Status Code (%s)', $statusCode));
        }

        $this->statusCode = $statusCode;

        return $this;
    }

    private function setMessage(string $message): self
    {
        $this->message = \trim($message);

        return $this;
    }

    private function setOrder($orderObject): self
    {
        $this->order = new Order(
            $orderObject->Pedido->ID,
            $orderObject->Pedido->Score,
            $orderObject->Pedido->Status,
            $orderObject->Pedido->URLQuestionario
        );

        return $this;
    }

    private function guardStatusCode(): void
    {
        if (self::STATUS_CODE_SUCCESS === $this->getStatusCode()) {
            return;
        }

        if (self::STATUS_CODE_ENTITY_CODE_NOT_FOUND === $this->getStatusCode()) {
            $this->setMessage('entity code not found');
        }

        $exceptionClass = static::$statusCodeExceptions[$this->getStatusCode()];

        throw new $exceptionClass($this->getMessage(), $this->getStatusCode());
    }
}
