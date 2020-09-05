<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class FingerPrint implements XmlEntityInterface
{
    /** @var  string */
    private $sessionId;

    public function __construct(string $sessionId)
    {
        $this->setSessionId($sessionId);
    }

    public static function create(string $sessionId): self
    {
        return new self($sessionId);
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $sessionId = \trim($sessionId);

        if (\strlen($sessionId) === 0) {
            throw new RequiredFieldException('Session ID is required');
        }

        $this->sessionId = $sessionId;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->writeElement('SessionID', $this->sessionId);
    }
}
