<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Passenger implements XmlEntityInterface
{
    public const DOCUMENT_TYPE_CPF = 1;
    public const DOCUMENT_TYPE_CNPJ = 2;
    public const DOCUMENT_TYPE_RG = 3;
    public const DOCUMENT_TYPE_IE = 4;
    public const DOCUMENT_TYPE_PASSAPORTE = 5;
    public const DOCUMENT_TYPE_CTPS = 6;
    public const DOCUMENT_TYPE_TITULO_ELEITOR = 7;

    private $documentTypes = [
        self::DOCUMENT_TYPE_CPF,
        self::DOCUMENT_TYPE_CNPJ,
        self::DOCUMENT_TYPE_RG,
        self::DOCUMENT_TYPE_IE,
        self::DOCUMENT_TYPE_PASSAPORTE,
        self::DOCUMENT_TYPE_CTPS,
        self::DOCUMENT_TYPE_TITULO_ELEITOR,
    ];

    /** @var  string */
    private $name;

    /** @var  ?string */
    private $frequentFlyerCard = null;

    /** @var  int */
    private $legalDocumentType;

    /** @var  string */
    private $legalDocument;

    public function __construct(string $name, int $legalDocumentType, string $legalDocument)
    {
        $this->setName($name);
        $this->setLegalDocumentType($legalDocumentType);
        $this->setLegalDocument($legalDocument);
    }

    public static function create(string $name, int $legalDocumentType, string $legalDocument): self
    {
        return new self($name, $legalDocumentType, $legalDocument);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $name = \trim($name);

        if (\strlen($name) === 0) {
            throw new RequiredFieldException('Name is required');
        }

        $this->name = $name;

        return $this;
    }

    public function getFrequentFlyerCard(): ?string
    {
        return $this->frequentFlyerCard;
    }

    public function setFrequentFlyerCard(string $frequentFlyerCard): self
    {
        $this->frequentFlyerCard = \trim($frequentFlyerCard) ?: null;

        return $this;
    }

    public function getLegalDocumentType(): int
    {
        return $this->legalDocumentType;
    }

    public function setLegalDocumentType(int $legalDocumentType): self
    {
        if (! \in_array($legalDocumentType, $this->documentTypes)) {
            throw new \InvalidArgumentException(
                \sprintf('Invalid document type (%s)', $legalDocumentType)
            );
        }

        $this->legalDocumentType = $legalDocumentType;

        return $this;
    }

    public function getLegalDocument(): string
    {
        return $this->legalDocument;
    }

    public function setLegalDocument(string $legalDocument): self
    {
        $legalDocument = \trim($legalDocument);

        if (\strlen($legalDocument) === 0) {
            throw new RequiredFieldException('Legal Document is required');
        }

        $this->legalDocument = $legalDocument;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('Passageiro');

        $XMLWriter->writeElement('Nome', $this->name);

        if ($this->frequentFlyerCard) {
            $XMLWriter->writeElement('ProgramaFidelidade', $this->frequentFlyerCard);
        }

        $XMLWriter->writeElement('TipoDocumentoLegal', $this->legalDocumentType);
        $XMLWriter->writeElement('DocumentoLegal', $this->legalDocument);

        $XMLWriter->endElement(); // Passageiro
    }
}
