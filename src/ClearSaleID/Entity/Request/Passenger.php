<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;
use XMLWriter;

class Passenger implements XmlEntityInterface
{
    const DOCUMENT_TYPE_CPF            = 1;
    const DOCUMENT_TYPE_CNPJ           = 2;
    const DOCUMENT_TYPE_RG             = 3;
    const DOCUMENT_TYPE_IE             = 4;
    const DOCUMENT_TYPE_PASSAPORTE     = 5;
    const DOCUMENT_TYPE_CTPS           = 6;
    const DOCUMENT_TYPE_TITULO_ELEITOR = 7;

    private $documentTypes = [
        self::DOCUMENT_TYPE_CPF,
        self::DOCUMENT_TYPE_CNPJ,
        self::DOCUMENT_TYPE_RG,
        self::DOCUMENT_TYPE_IE,
        self::DOCUMENT_TYPE_PASSAPORTE,
        self::DOCUMENT_TYPE_CTPS,
        self::DOCUMENT_TYPE_TITULO_ELEITOR
    ];

    /** @var  string */
    private $name;

    /** @var  string */
    private $frequentFlyerCard;

    /** @var  int */
    private $legalDocumentType;

    /** @var  string */
    private $legalDocument;

    /**
     * @param  string $name
     * @param  int    $legalDocumentType
     * @param  string $legalDocument
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Passenger
     */
    public static function create( $name, $legalDocumentType, $legalDocument )
    {
        $instance = new self;

        $instance->setName( $name );
        $instance->setLegalDocumentType( $legalDocumentType );
        $instance->setLegalDocument( $legalDocument );

        return $instance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     *
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrequentFlyerCard()
    {
        return $this->frequentFlyerCard;
    }

    /**
     * @param  string $frequentFlyerCard
     *
     * @return $this
     */
    public function setFrequentFlyerCard( $frequentFlyerCard )
    {
        $this->frequentFlyerCard = $frequentFlyerCard;

        return $this;
    }

    /**
     * @return int
     */
    public function getLegalDocumentType()
    {
        return $this->legalDocumentType;
    }

    /**
     * @param  int $legalDocumentType
     *
     * @return $this
     */
    public function setLegalDocumentType( $legalDocumentType )
    {
        if (!in_array( intval( $legalDocumentType ), $this->documentTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid document type (%s)', $legalDocumentType ) );
        }

        $this->legalDocumentType = $legalDocumentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getLegalDocument()
    {
        return $this->legalDocument;
    }

    /**
     * @param  string $legalDocument
     *
     * @return $this
     */
    public function setLegalDocument( $legalDocument )
    {
        $this->legalDocument = $legalDocument;

        return $this;
    }

    /**
     * @param  \XMLWriter $XMLWriter
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'Passageiro' );

        if ($this->name) {
            $XMLWriter->writeElement( 'Nome', $this->name );
        } else {
            throw new RequiredFieldException( 'Field Nome of the Passenger object is required' );
        }

        if ($this->frequentFlyerCard) {
            $XMLWriter->writeElement( 'ProgramaFidelidade', $this->frequentFlyerCard );
        }

        if ($this->legalDocumentType) {
            $XMLWriter->writeElement( 'TipoDocumentoLegal', $this->legalDocumentType );
        } else {
            throw new RequiredFieldException( 'Field LegalDocumentType of the Passenger object is required' );
        }

        if ($this->legalDocument) {
            $XMLWriter->writeElement( 'DocumentoLegal', $this->legalDocument );
        } else {
            throw new RequiredFieldException( 'Field LegalDocument of the Passenger object is required' );
        }

        $XMLWriter->endElement();
    }
}
