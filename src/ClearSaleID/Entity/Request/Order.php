<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Order implements XmlEntityInterface
{
    public const ECOMMERCE_B2B = 'b2b';
    public const ECOMMERCE_B2C = 'b2c';
    public const STATUS_NEW = 0;
    public const STATUS_APPROVED = 9;
    public const STATUS_CANCELLED = 41;
    public const STATUS_REJECTED = 45;

    private static $ecommerceTypes = [
        self::ECOMMERCE_B2B,
        self::ECOMMERCE_B2C,
    ];

    private static $statuses = [
        self::STATUS_NEW,
        self::STATUS_APPROVED,
        self::STATUS_CANCELLED,
        self::STATUS_REJECTED,
    ];

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint */
    private $fingerPrint;

    /** @var  string */
    private $id;

    /** @var  \DateTimeInterface */
    private $date;

    /** @var  string|null */
    private $email = null;

    /** @var  string|null */
    private $channel = null;

    /** @var  string|null */
    private $ecommerceType = null;

    /** @var  float|null */
    private $shippingValue = null;

    /** @var  float */
    private $totalItems;

    /** @var  float */
    private $totalOrder;

    /** @var  int|null */
    private $quantityInstallments = null;

    /** @var  string|null */
    private $deliveryTime = null;

    /** @var  int|null */
    private $quantityItems = null;

    /** @var  int|null */
    private $quantityPaymentTypes = null;

    /** @var  string|null */
    private $ip = null;

    /** @var  string|null */
    private $notes = null;

    /** @var  int|null */
    private $status = null;

    /** @var  string|null */
    private $origin = null;

    /** @var  \DateTimeInterface|null */
    private $reservationDate = null;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData */
    private $customerBillingData;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData */
    private $customerShippingData;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Payment[] */
    private $payments = [];

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Item[] */
    private $items = [];

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger[] */
    private $passengers = [];

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Connection[] */
    private $connections = [];

    public function __construct(
        FingerPrint $fingerPrint,
        string $id,
        \DateTimeInterface $date,
        float $totalItems,
        float $totalOrder,
        CustomerBillingData $billingData,
        CustomerShippingData $shippingData,
        Payment $payment,
        Item $item
    ) {
        $this->setFingerPrint($fingerPrint);
        $this->setId($id);
        $this->setDate($date);
        $this->setTotalItems($totalItems);
        $this->setTotalOrder($totalOrder);
        $this->setBillingData($billingData);
        $this->setShippingData($shippingData);
        $this->addPayment($payment);
        $this->addItem($item);
    }

    public static function createEcommerceOrder(
        FingerPrint $fingerPrint,
        string $id,
        \DateTimeInterface $date,
        string $email,
        float $totalItems,
        float $totalOrder,
        int $quantityInstallments,
        string $ip,
        string $origin,
        CustomerBillingData $billingData,
        CustomerShippingData $shippingData,
        Payment $payment,
        Item $item
    ): self {
        $instance = new self(
            $fingerPrint,
            $id,
            $date,
            $totalItems,
            $totalOrder,
            $billingData,
            $shippingData,
            $payment,
            $item
        );

        $instance->setEmail($email);
        $instance->setQuantityInstallments($quantityInstallments);
        $instance->setIp($ip);
        $instance->setOrigin($origin);

        return $instance;
    }

    public static function createAirlineTicketOrder(
        FingerPrint $fingerPrint,
        string $id,
        \DateTimeInterface $date,
        string $email,
        float $totalItems,
        float $totalOrder,
        int $quantityInstallments,
        string $ip,
        string $origin,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $customerShippingData,
        Payment $payment,
        Item $item,
        Passenger $passenger,
        Connection $connection
    ): self {
        $instance = static::createEcommerceOrder(
            $fingerPrint,
            $id,
            $date,
            $email,
            $totalItems,
            $totalOrder,
            $quantityInstallments,
            $ip,
            $origin,
            $customerBillingData,
            $customerShippingData,
            $payment,
            $item
        );

        $instance->addPassenger($passenger);
        $instance->addConnection($connection);

        return $instance;
    }

    public function getFingerPrint(): FingerPrint
    {
        return $this->fingerPrint;
    }

    public function setFingerPrint(FingerPrint $fingerPrint): self
    {
        $this->fingerPrint = $fingerPrint;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $id = \trim($id);

        if (\strlen($id) === 0) {
            throw new RequiredFieldException('Order ID is required');
        }

        $this->id = $id;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = \trim($email) ?: null;

        return $this;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = \trim($channel) ?: null;

        return $this;
    }

    public function getEcommerceType(): ?string
    {
        return $this->ecommerceType;
    }

    public function setEcommerceType(string $ecommerceType): self
    {
        if (! \in_array($ecommerceType, self::$ecommerceTypes)) {
            throw new \InvalidArgumentException(
                \sprintf('Invalid e-commerce type (%s)', $ecommerceType)
            );
        }

        $this->ecommerceType = $ecommerceType;

        return $this;
    }

    public function getShippingValue(): ?float
    {
        return $this->shippingValue;
    }

    public function setShippingValue(float $shippingValue): self
    {
        if ($shippingValue < 0.0) {
            throw new \InvalidArgumentException(
                \sprintf('Shipping value value should be a non-negative number (%s)', $shippingValue)
            );
        }

        $this->shippingValue = \floatval(\number_format($shippingValue, 4, '.', ''));

        return $this;
    }

    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    public function setTotalItems(float $totalItems): self
    {
        if ($totalItems < 0.0) {
            throw new \InvalidArgumentException(
                \sprintf('Items total value should be a non-negative number (%s)', $totalItems)
            );
        }

        $this->totalItems = \floatval(\number_format($totalItems, 4, '.', ''));

        return $this;
    }

    public function getTotalOrder(): float
    {
        return $this->totalOrder;
    }

    public function setTotalOrder(float $totalOrder): self
    {
        if ($totalOrder < 0.0) {
            throw new \InvalidArgumentException(
                \sprintf('Order total value should be a non-negative number (%s)', $totalOrder)
            );
        }

        $this->totalOrder = \floatval(\number_format($totalOrder, 4, '.', ''));

        return $this;
    }

    public function getQuantityInstallments(): ?int
    {
        return $this->quantityInstallments;
    }

    public function setQuantityInstallments(int $quantityInstallments): self
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

    public function getDeliveryTime(): ?string
    {
        return $this->deliveryTime;
    }

    public function setDeliveryTime(string $deliveryTime): self
    {
        $this->deliveryTime = \trim($deliveryTime) ?: null;

        return $this;
    }

    public function getQuantityItems(): ?int
    {
        return $this->quantityItems;
    }

    public function setQuantityItems(int $quantityItems): self
    {
        if ($quantityItems < 0) {
            throw new \InvalidArgumentException(
                \sprintf('Items quantity should be a non-negative integer (%s)', $quantityItems)
            );
        }

        $this->quantityItems = $quantityItems;

        return $this;
    }

    public function getQuantityPaymentTypes(): ?int
    {
        return $this->quantityPaymentTypes;
    }

    public function setQuantityPaymentTypes(int $quantityPaymentTypes): self
    {
        if ($quantityPaymentTypes < 0) {
            throw new \InvalidArgumentException(
                \sprintf('Payment types quantity should be a non-negative integer (%s)', $quantityPaymentTypes)
            );
        }

        $this->quantityPaymentTypes = $quantityPaymentTypes;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = \trim($ip) ?: null;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = \trim($notes) ?: null;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (! \in_array($status, self::$statuses)) {
            throw new \InvalidArgumentException(\sprintf('Invalid status (%s)', $status));
        }

        $this->status = $status;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = \trim($origin) ?: null;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservationDate;
    }

    public function setReservationDate(\DateTimeInterface $reservationDate): self
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    public function getBillingData(): CustomerBillingData
    {
        return $this->customerBillingData;
    }

    public function setBillingData(CustomerBillingData $customerBillingData): self
    {
        $this->customerBillingData = $customerBillingData;

        return $this;
    }

    public function getShippingData(): CustomerShippingData
    {
        return $this->customerShippingData;
    }

    public function setShippingData(CustomerShippingData $customerShippingData): self
    {
        $this->customerShippingData = $customerShippingData;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function setPayments(iterable $payments): self
    {
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }

        if (\count($this->payments) === 0) {
            throw new RequiredFieldException('Order requires at least one payment');
        }

        return $this;
    }

    public function getPayment(int $index): Payment
    {
        return $this->payments[$index];
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(iterable $items): self
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        if (\count($this->items) === 0) {
            throw new RequiredFieldException('Order requires at least one item');
        }

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Passenger[]
     */
    public function getPassengers(): array
    {
        return $this->passengers;
    }

    public function setPassengers(iterable $passengers): self
    {
        foreach ($passengers as $passenger) {
            $this->addPassenger($passenger);
        }

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Connection[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }

    public function setConnections(iterable $connections): self
    {
        foreach ($connections as $connection) {
            $this->addConnection($connection);
        }

        return $this;
    }

    public function addPayment(Payment $payment): self
    {
        $this->payments[] = $payment;

        return $this;
    }

    public function addItem(Item $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function addPassenger(Passenger $passenger): self
    {
        $this->passengers[] = $passenger;

        return $this;
    }

    public function addConnection(Connection $connection): self
    {
        $this->connections[] = $connection;

        return $this;
    }

    public function toXML(\XMLWriter $XMLWriter): void
    {
        $XMLWriter->startElement('ClearID_Input');

        $this->fingerPrint->toXML($XMLWriter);

        $XMLWriter->startElement('Pedido');

        $XMLWriter->writeElement('PedidoID', $this->id);
        $XMLWriter->writeElement('Data', $this->date->format(self::DATE_TIME_FORMAT));

        if ($this->email) {
            $XMLWriter->writeElement('Email', $this->email);
        }

        if ($this->channel) {
            $XMLWriter->writeElement('CanalID', $this->channel);
        }

        if ($this->ecommerceType) {
            $XMLWriter->writeElement('B2B_B2C', $this->ecommerceType);
        }

        if ($this->shippingValue) {
            $XMLWriter->writeElement('ValorFrete', $this->shippingValue);
        }

        $XMLWriter->writeElement('ValorTotalItens', $this->totalItems);
        $XMLWriter->writeElement('ValorTotalPedido', $this->totalOrder);

        if ($this->quantityInstallments) {
            $XMLWriter->writeElement('QtdParcelas', $this->quantityInstallments);
        }

        if ($this->deliveryTime) {
            $XMLWriter->writeElement('PrazoEntrega', $this->deliveryTime);
        }

        if ($this->quantityItems) {
            $XMLWriter->writeElement('QtdItens', $this->quantityItems);
        }

        if ($this->quantityPaymentTypes) {
            $XMLWriter->writeElement('QtdFormasPagamento', $this->quantityPaymentTypes);
        }

        if ($this->ip) {
            $XMLWriter->writeElement('IP', $this->ip);
        }

        if ($this->notes) {
            $XMLWriter->writeElement('Observacao', $this->notes);
        }

        if ($this->status) {
            $XMLWriter->writeElement('Status', $this->status);
        }

        if ($this->origin) {
            $XMLWriter->writeElement('Origem', $this->origin);
        }

        if ($this->reservationDate) {
            $XMLWriter->writeElement('DataReserva', $this->reservationDate->format(self::DATE_TIME_FORMAT));
        }

        $this->customerBillingData->toXML($XMLWriter);
        $this->customerShippingData->toXML($XMLWriter);

        $XMLWriter->startElement('Pagamentos');

        foreach ($this->payments as $payment) {
            $payment->toXML($XMLWriter);
        }

        $XMLWriter->endElement(); // Pagamentos

        $XMLWriter->startElement('Itens');

        foreach ($this->items as $item) {
            $item->toXML($XMLWriter);
        }

        $XMLWriter->endElement(); // Itens

        if (\count($this->passengers) > 0) {
            $XMLWriter->startElement('Passageiros');

            foreach ($this->passengers as $passenger) {
                $passenger->toXML($XMLWriter);
            }

            $XMLWriter->endElement(); // Passageiros
        }

        if (\count($this->connections) > 0) {
            $XMLWriter->startElement('Conexoes');

            foreach ($this->connections as $connection) {
                $connection->toXML($XMLWriter);
            }

            $XMLWriter->endElement(); // Conexoes
        }

        $XMLWriter->endElement(); // Pedido
        $XMLWriter->endElement(); // ClearID_Input
    }
}
