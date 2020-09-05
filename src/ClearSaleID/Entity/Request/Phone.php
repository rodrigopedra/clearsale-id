<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class Phone implements XmlEntityInterface
{
    public const NAO_DEFINIDO = 0;
    public const RESIDENCIAL = 1;
    public const COMERCIAL = 2;
    public const RECADOS = 3;
    public const COBRANCA = 4;
    public const TEMPORARIO = 5;
    public const CELULAR = 6;

    private static $types = [
        self::NAO_DEFINIDO,
        self::RESIDENCIAL,
        self::COMERCIAL,
        self::RECADOS,
        self::COBRANCA,
        self::TEMPORARIO,
        self::CELULAR,
    ];

    /** @var  int */
    private $type;

    /** @var  string|null */
    private $ddi = null;

    /** @var  string */
    private $ddd;

    /** @var  string */
    private $number;

    /** @var  string|null */
    private $extension = null;

    public function __construct(int $type, string $ddd, string $number)
    {
        $this->setType($type);
        $this->setDDD($ddd);
        $this->setNumber($number);
    }

    public static function create(int $type, string $ddd, string $number): self
    {
        return new self($type, $ddd, $number);
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (! \in_array($type, self::$types)) {
            throw new \InvalidArgumentException(\sprintf('Invalid type (%s)', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getDDI(): ?string
    {
        return $this->ddi;
    }

    public function setDDI(string $ddi): self
    {
        $ddi = \preg_replace('/^0+|\D/', '', $ddi);

        if (\strlen($ddi) < 1 || \strlen($ddi) > 3) {
            throw new \InvalidArgumentException(\sprintf('Invalid DDI (%s)', $ddi));
        }

        $this->ddi = $ddi;

        return $this;
    }

    public function getDDD(): string
    {
        return $this->ddd;
    }

    public function setDDD(string $ddd): self
    {
        $ddd = \preg_replace('/^0+|\D/', '', $ddd);

        if (\strlen($ddd) !== 2) {
            throw new \InvalidArgumentException(\sprintf('Invalid DDD (%s)', $ddd));
        }

        $this->ddd = $ddd;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $number = \preg_replace('/^0+|\D/', '', $number);

        if (\strlen($number) !== 9 && \strlen($number) !== 8) {
            throw new \InvalidArgumentException(\sprintf('Invalid Number (%s)', $number));
        }

        $this->number = $number;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $extension = \trim($extension);

        if (\strlen($extension) > 5) {
            throw new \InvalidArgumentException(
                \sprintf('Extension should be no longer than 5 characters (%s)', $extension)
            );
        }

        $this->extension = $extension ?: null;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('Telefone');

        $XMLWriter->writeElement('Tipo', $this->type);

        if ($this->ddi) {
            $XMLWriter->writeElement('DDI', $this->ddi);
        }

        $XMLWriter->writeElement('DDD', $this->ddd);
        $XMLWriter->writeElement('Numero', $this->number);

        if ($this->extension) {
            $XMLWriter->writeElement('Ramal', $this->extension);
        }

        $XMLWriter->endElement(); // Telefone
    }
}
