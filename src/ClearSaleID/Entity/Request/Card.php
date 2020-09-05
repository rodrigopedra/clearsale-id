<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class Card implements XmlEntityInterface
{
    public const DINERS = 1;
    public const MASTERCARD = 2;
    public const VISA = 3;
    public const OUTROS = 4;
    public const AMERICAN_EXPRESS = 5;
    public const HIPERCARD = 6;
    public const AURA = 7;

    private static $cardTypes = [
        self::DINERS,
        self::MASTERCARD,
        self::VISA,
        self::OUTROS,
        self::AMERICAN_EXPRESS,
        self::HIPERCARD,
        self::AURA,
    ];

    /** @var  string|null */
    private $numberHash = null;

    /** @var  string|null */
    private $bin = null;

    /** @var  string|null */
    private $lastDigits = null;

    /** @var  string|null */
    private $type = null;

    /** @var  string|null */
    private $expirationDate = null;

    /** @var  string|null */
    private $name = null;

    public static function create(): self
    {
        return new self();
    }

    public function getNumberHash(): ?string
    {
        return $this->numberHash;
    }

    public function setNumberHash(string $numberHash): self
    {
        $numberHash = \trim($numberHash);

        if (\strlen($numberHash) > 40) {
            throw new \InvalidArgumentException(
                \sprintf('Card number hash should be no longer than 40 characters (%s)', $numberHash)
            );
        }

        $this->numberHash = $numberHash ?: null;

        return $this;
    }

    public function getBin(): ?string
    {
        return $this->bin;
    }

    public function setBin(string $bin): self
    {
        $bin = \trim($bin);

        if (\strlen($bin) !== 6) {
            throw new \InvalidArgumentException(
                \sprintf('Bin number should contain the first 6 characters in the card number (%s)', $bin)
            );
        }

        $this->bin = $bin;

        return $this;
    }

    public function getLastDigits(): ?string
    {
        return $this->lastDigits;
    }

    public function setLastDigits(string $lastDigits): self
    {
        $lastDigits = \trim($lastDigits);

        if (\strlen($lastDigits) !== 4) {
            throw new \InvalidArgumentException(
                \sprintf('Last digits should contain the last 4 characters in the card number (%s)', $lastDigits)
            );
        }

        $this->lastDigits = $lastDigits;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (! \in_array($type, self::$cardTypes)) {
            throw new \InvalidArgumentException(\sprintf('Invalid type (%s)', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): self
    {
        $this->expirationDate = \trim($expirationDate) ?: null;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = \trim($name) ?: null;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        if ($this->numberHash) {
            $XMLWriter->writeElement('HashNumeroCartao', $this->numberHash);
        }

        if ($this->bin) {
            $XMLWriter->writeElement('BinCartao', $this->bin);
        }

        if ($this->lastDigits) {
            $XMLWriter->writeElement('Cartao4Ultimos', $this->lastDigits);
        }

        if ($this->type) {
            $XMLWriter->writeElement('TipoCartao', $this->type);
        }

        if ($this->expirationDate) {
            $XMLWriter->writeElement('DataValidadeCartao', $this->expirationDate);
        }

        if ($this->name) {
            $XMLWriter->writeElement('NomeTitularCartao', $this->name);
        }
    }
}
