<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use XMLWriter;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

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

    /** @var  DateTime */
    private $date;

    /** @var  float */
    private $amount;

    /** @var  int */
    private $quantityInstallments;

    /** @var  Card */
    private $card;

    /** @var  string */
    private $legalDocument;

    public static function create( $type, DateTime $date, $amount )
    {
        $instance = new self();

        $instance->setType( $type );
        $instance->setDate( $date );
        $instance->setAmount( $amount );

        return $instance;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType( $type )
    {
        if (!array_key_exists( intval( $type ), self::$paymentTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid payment type (%s)', $type ) );
        }

        $this->type = $type;

        return $this;
    }

    public function getSequential()
    {
        return $this->sequential;
    }

    public function setSequential( $sequential )
    {
        if (preg_match( '/^\d+$/', $sequential ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Sequential number should be a positive integer (%s)',
                $sequential ) );
        }

        $sequential = intval( $sequential );

        if ($sequential < 1 || $sequential > 9) {
            throw new InvalidArgumentException( sprintf( 'Sequential number should be between 1 and 9 (%s)',
                $sequential ) );
        }

        $this->sequential = $sequential;

        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @param DateTime $date
     *
     * @return Payment
     */
    public function setDate( DateTime $date )
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount( $amount )
    {
        if (preg_match( '/^(?:\d*\.)?\d+$/', $amount ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Amount should be a positive number (%s)', $amount ) );
        }

        $this->amount = (float)number_format( $amount, 4, '.', '' );

        return $this;
    }

    public function getQtyInstallments()
    {
        return $this->quantityInstallments;
    }

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
     *
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     *
     * @param Card $card
     *
     * @return Payment
     */
    public function setCard( Card $card )
    {
        $this->card = $card;

        return $this;
    }

    public function getLegalDocument()
    {
        return $this->legalDocument;
    }

    public function setLegalDocument( $legalDocument )
    {
        $this->legalDocument = $legalDocument;

        return $this;
    }

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

        if ($this->amount) {
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
