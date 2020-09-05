<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class Payment implements XmlEntityInterface
{
    public const CARTAO_CREDITO = 1;
    public const BOLETO_BANCARIO = 2;
    public const DEBITO_BANCARIO = 3;
    public const DEBITO_BANCARIO_DINHEIRO = 4;
    public const DEBITO_BANCARIO_CHEQUE = 5;
    public const TRANSFERENCIA_BANCARIA = 6;
    public const SEDEX_A_COBRAR = 7;
    public const CHEQUE = 8;
    public const DINHEIRO = 9;
    public const FINANCIAMENTO = 10;
    public const FATURA = 11;
    public const CUPOM = 12;
    public const MULTICHEQUE = 13;
    public const OUTROS = 14;

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

    /** @var  int|null */
    private $sequential = null;

    /** @var  \DateTimeInterface */
    private $date;

    /** @var  float */
    private $amount;

    /** @var  int|null */
    private $quantityInstallments = null;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Card|null */
    private $card = null;

    /** @var  string|null */
    private $legalDocument = null;

    public function __construct(string $type, \DateTimeInterface $date, float $amount)
    {
        $this->setType($type);
        $this->setDate($date);
        $this->setAmount($amount);
    }

    public static function create(string $type, \DateTimeInterface $date, float $amount): self
    {
        return new self($type, $date, $amount);
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (! \array_key_exists($type, self::$paymentTypes)) {
            throw new \InvalidArgumentException(\sprintf('Invalid payment type (%s)', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getSequential(): ?int
    {
        return $this->sequential;
    }

    public function setSequential(int $sequential): self
    {
        if (! \in_array($sequential, \range(1, 9))) {
            throw new \InvalidArgumentException(
                \sprintf('Sequential number should be between 1 and 9 (%s)', $sequential)
            );
        }

        $this->sequential = $sequential;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        if ($amount < 0.0) {
            throw new \InvalidArgumentException(
                \sprintf('Amount should be a non-negative number (%s)', $amount)
            );
        }

        $this->amount = \floatval(\number_format($amount, 4, '.', ''));

        return $this;
    }

    public function getQtyInstallments(): ?int
    {
        return $this->quantityInstallments;
    }

    public function setQtyInstallments(int $quantityInstallments): self
    {
        if ($quantityInstallments < 0) {
            throw new \InvalidArgumentException(
                \sprintf('Installments quantity should be a non-negative integer (%s)', $quantityInstallments)
            );
        }

        if ($quantityInstallments > 99) {
            throw new \InvalidArgumentException(
                \sprintf('Installments quantity should be less than 99 (%s)', $quantityInstallments)
            );
        }

        $this->quantityInstallments = $quantityInstallments;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getLegalDocument(): ?string
    {
        return $this->legalDocument;
    }

    public function setLegalDocument(string $legalDocument): self
    {
        $this->legalDocument = $legalDocument;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('Pagamento');

        if ($this->sequential) {
            $XMLWriter->writeElement('NumeroSequencial', $this->sequential);
        }

        $XMLWriter->writeElement('Data', $this->date->format(self::DATE_TIME_FORMAT));
        $XMLWriter->writeElement('Valor', $this->amount);
        $XMLWriter->writeElement('TipoPagamentoID', $this->type);

        if ($this->quantityInstallments) {
            $XMLWriter->writeElement('QtdParcelas', $this->quantityInstallments);
        }

        if ($this->card) {
            $this->card->toXML($XMLWriter);
        }

        if ($this->legalDocument) {
            $XMLWriter->writeElement('DocumentoLegal1', $this->legalDocument);
        }

        $XMLWriter->endElement(); // Pagamento
    }
}
