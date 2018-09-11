<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;
use XMLWriter;

class Payment implements XmlEntityInterface
{
    const CARTAO_CREDITO           = 1;
    const BOLETO_BANCARIO          = 2;
    const DEBITO_BANCARIO          = 3;
    const DEBITO_BANCARIO_DINHEIRO = 4;
    const DEBITO_BANCARIO_CHEQUE   = 5;
    const TRANSFERENCIA_BANCARIA   = 6;
    const SEDEX_A_COBRAR           = 7;
    const CHEQUE                   = 8;
    const DINHEIRO                 = 9;
    const FINANCIAMENTO            = 10;
    const FATURA                   = 11;
    const CUPOM                    = 12;
    const MULTICHEQUE              = 13;
    const OUTROS                   = 14;

    private static $paymentTypes = [
        self::CARTAO_CREDITO,
        self::BOLETO_BANCARIO,
        self::DEBITO_BANCARIO,
        self::DEBITO_BANCARIO_DINHEIRO,
        self::DEBITO_BANCARIO_CHEQUE,
        self::TRANSFERENCIA_BANCARIA,
        self::SEDEX_A_COBRAR,
        self::CHEQUE,
        self::DINHEIRO,
        self::FINANCIAMENTO,
        self::FATURA,
        self::CUPOM,
        self::MULTICHEQUE,
        self::OUTROS,
    ];

    /** @var  int */
    private $type;

    /** @var  int */
    private $sequential;

    /** @var  \DateTime */
    private $date;

    /** @var  float */
    private $amount;

    /** @var  int */
    private $quantityInstallments;

    /** @var \RodrigoPedra\ClearSaleID\Entity\Request\ Card */
    private $card;

    /** @var  string */
    private $legalDocument;

    /**
     * @param  int       $type
     * @param  \DateTime $date
     * @param  float     $amount
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Payment
     */
    public static function create( $type, DateTime $date, $amount )
    {
        $instance = new self;

        $instance->setType( $type );
        $instance->setDate( $date );
        $instance->setAmount( $amount );

        return $instance;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  int $type
     *
     * @return $this
     */
    public function setType( $type )
    {
        if (!array_key_exists( intval( $type ), self::$paymentTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid payment type (%s)', $type ) );
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequential()
    {
        return $this->sequential;
    }

    /**
     * @param  int $sequential
     *
     * @return $this
     */
    public function setSequential( $sequential )
    {
        if (preg_match( '/^[1-9]$/', $sequential ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Sequential number should be between 1 and 9 (%s)',
                $sequential ) );
        }

        $this->sequential = intval( $sequential );

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param  \DateTime $date
     *
     * @return $this
     */
    public function setDate( DateTime $date )
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param  float $amount
     *
     * @return $this
     */
    public function setAmount( $amount )
    {
        if (preg_match( '/^(?:\d*\.)?\d+$/', $amount ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Amount should be a non-negative number (%s)', $amount ) );
        }

        $this->amount = (float)number_format( $amount, 4, '.', '' );

        return $this;
    }

    /**
     * @return int
     */
    public function getQtyInstallments()
    {
        return $this->quantityInstallments;
    }

    /**
     * @param  int $quantityInstallments
     *
     * @return $this
     */
    public function setQtyInstallments( $quantityInstallments )
    {
        if (preg_match( '/^\d+$/', $quantityInstallments ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Installments quantity should be a non-negative integer (%s)',
                $quantityInstallments ) );
        }

        $quantityInstallments = intval( $quantityInstallments );

        if ($quantityInstallments > 99) {
            throw new InvalidArgumentException( sprintf( 'Installments quantity should be less than 99 (%s)',
                $quantityInstallments ) );
        }

        $this->quantityInstallments = $quantityInstallments;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Card $card
     *
     * @return $this
     */
    public function setCard( Card $card )
    {
        $this->card = $card;

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
        $XMLWriter->startElement( 'Pagamento' );

        if ($this->sequential) {
            $XMLWriter->writeElement( 'NumeroSequencial', $this->sequential );
        }

        if ($this->date) {
            $XMLWriter->writeElement( 'Data', $this->date->format( self::DATE_TIME_FORMAT ) );
        } else {
            throw new RequiredFieldException( 'Field Date of the Payment object is required' );
        }

        if (is_numeric( $this->amount ) && $this->amount >= 0.0) {
            $XMLWriter->writeElement( 'Valor', $this->amount );
        } else {
            throw new RequiredFieldException( 'Field Amount of the Payment object is required' );
        }

        if ($this->type) {
            $XMLWriter->writeElement( 'TipoPagamentoID', $this->type );
        } else {
            throw new RequiredFieldException( 'Field PaymentTypeID of the Payment object is required' );
        }

        if ($this->quantityInstallments) {
            $XMLWriter->writeElement( 'QtdParcelas', $this->quantityInstallments );
        }

        if ($this->card) {
            $this->card->toXML( $XMLWriter );
        }

        if ($this->legalDocument) {
            $XMLWriter->writeElement( 'DocumentoLegal1', $this->legalDocument );
        }

        $XMLWriter->endElement();
    }
}
