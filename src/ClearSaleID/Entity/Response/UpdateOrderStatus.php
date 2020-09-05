<?php

namespace RodrigoPedra\ClearSaleID\Entity\Response;

use RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException;
use RodrigoPedra\ClearSaleID\Exception\UpdateOrderStatusException;

class UpdateOrderStatus
{
    public const STATUS_CODE_OK = 'OK';

    /** @var  string */
    private $statusCode;

    /** @var  string */
    private $message;

    public function __construct(string $xml)
    {
        try {
            // FIX PHP Warning: Parser error : Document labelled UTF-16 but has UTF-8 content
            $xml = \preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

            $object = \simplexml_load_string($xml);
            $updateOrderStatusObject = \json_decode(\json_encode($object));

            if (\is_null($updateOrderStatusObject)) {
                throw new UnexpectedErrorException(\sprintf('Invalid response from webservice (%s)', $xml), 0);
            }

            $this->setStatusCode($updateOrderStatusObject->StatusCode);
            $this->setMessage($updateOrderStatusObject->Message);
        } catch (\Throwable $exception) {
            throw new UnexpectedErrorException(\sprintf('Invalid response from webservice (%s)', $xml), 0, $exception);
        }

        $this->guardStatusCode();
    }

    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === self::STATUS_CODE_OK;
    }

    private function setStatusCode(string $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    private function setMessage(string $message): self
    {
        $this->message = \trim($message);

        return $this;
    }

    private function guardStatusCode(): void
    {
        if ($this->getStatusCode() === self::STATUS_CODE_OK) {
            return;
        }

        throw new UpdateOrderStatusException(\sprintf('Update order status failed (%s)', $this->getStatusCode()));
    }
}
