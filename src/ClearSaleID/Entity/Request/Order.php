<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;
use XMLWriter;

class Order implements XmlEntityInterface
{
    const ECOMMERCE_B2B    = 'b2b';
    const ECOMMERCE_B2C    = 'b2c';
    const STATUS_NEW       = 0;
    const STATUS_APPROVED  = 9;
    const STATUS_CANCELLED = 41;
    const STATUS_REJECTED  = 45;

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

    /** @var  \DateTime */
    private $date;

    /** @var  string */
    private $email;

    /** @var  string */
    private $channel;

    /** @var  string */
    private $ecommerceType;

    /** @var  float */
    private $shippingValue;

    /** @var  float */
    private $totalItems;

    /** @var  float */
    private $totalOrder;

    /** @var  int */
    private $quantityInstallments;

    /** @var  string */
    private $deliveryTime;

    /** @var  int */
    private $quantityItems;

    /** @var  int */
    private $quantityPaymentTypes;

    /** @var  string */
    private $ip;

    /** @var  string */
    private $notes;

    /** @var  int */
    private $status;

    /** @var  string */
    private $origin;

    /** @var  \DateTime */
    private $reservationDate;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData */
    private $customerBillingData;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData */
    private $customerShippingData;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Payment[] */
    private $payments;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Item[] */
    private $items;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger[] */
    private $passengers;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Connection[] */
    private $connections;

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint          $fingerPrint
     * @param  int                                                           $id
     * @param  \DateTime                                                     $date
     * @param  string                                                        $email
     * @param  float                                                         $totalItems
     * @param  float                                                         $totalOrder
     * @param  int                                                           $quantityInstallments
     * @param  string                                                        $ip
     * @param  string                                                        $origin
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData  $customerBillingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData $customerShippingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Payment              $payment
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Item                 $item
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Order
     */
    public static function createEcommerceOrder(
        FingerPrint $fingerPrint,
        $id,
        DateTime $date,
        $email,
        $totalItems,
        $totalOrder,
        $quantityInstallments,
        $ip,
        $origin,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $customerShippingData,
        Payment $payment,
        Item $item
    ) {
        return static::create(
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
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint          $fingerPrint
     * @param  int                                                           $id
     * @param  \DateTime                                                     $date
     * @param  string                                                        $email
     * @param  float                                                         $totalItems
     * @param  float                                                         $totalOrder
     * @param  int                                                           $quantityInstallments
     * @param  string                                                        $ip
     * @param  string                                                        $origin
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData  $customerBillingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData $customerShippingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Payment              $payment
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Item                 $item
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger            $passenger
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Connection           $connection
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Order
     */
    public static function createAirlineTicketOrder(
        FingerPrint $fingerPrint,
        $id,
        DateTime $date,
        $email,
        $totalItems,
        $totalOrder,
        $quantityInstallments,
        $ip,
        $origin,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $customerShippingData,
        Payment $payment,
        Item $item,
        Passenger $passenger = null,
        Connection $connection = null
    ) {
        return static::create(
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
            $item,
            $passenger,
            $connection
        );
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint          $fingerPrint
     * @param  int                                                           $id
     * @param  \DateTime                                                     $date
     * @param  string                                                        $email
     * @param  float                                                         $totalItems
     * @param  float                                                         $totalOrder
     * @param  int                                                           $quantityInstallments
     * @param  string                                                        $ip
     * @param  string                                                        $origin
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData  $customerBillingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData $shippingData
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Payment              $payment
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Item                 $item
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger|null       $passenger
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Connection|null      $connection
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Order
     */
    private static function create(
        FingerPrint $fingerPrint,
        $id,
        DateTime $date,
        $email,
        $totalItems,
        $totalOrder,
        $quantityInstallments,
        $ip,
        $origin,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $shippingData,
        Payment $payment,
        Item $item,
        Passenger $passenger = null,
        Connection $connection = null
    ) {
        $instance = new self;

        $instance->setFingerPrint( $fingerPrint );
        $instance->setId( $id );
        $instance->setDate( $date );
        $instance->setEmail( $email );
        $instance->setTotalItems( $totalItems );
        $instance->setTotalOrder( $totalOrder );
        $instance->setQuantityInstallments( $quantityInstallments );
        $instance->setIp( $ip );
        $instance->setOrigin( $origin );
        $instance->setBillingData( $customerBillingData );
        $instance->setShippingData( $shippingData );
        $instance->addPayment( $payment );
        $instance->addItem( $item );

        if (null !== $passenger) {
            $instance->addPassenger( $passenger );
        }

        if (null !== $connection) {
            $instance->addConnection( $connection );
        }

        return $instance;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint
     */
    public function getFingerPrint()
    {
        return $this->fingerPrint;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint $fingerPrint
     *
     * @return $this
     */
    public function setFingerPrint( FingerPrint $fingerPrint )
    {
        $this->fingerPrint = $fingerPrint;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int $id
     *
     * @return $this
     */
    public function setId( $id )
    {
        $this->id = $id;

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param  string $email
     *
     * @return $this
     */
    public function setEmail( $email )
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param  string $channel
     *
     * @return $this
     */
    public function setChannel( $channel )
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return string
     */
    public function getEcommerceType()
    {
        return $this->ecommerceType;
    }

    /**
     * @param  string $ecommerceType
     *
     * @return $this
     */
    public function setEcommerceType( $ecommerceType )
    {
        if (!in_array( $ecommerceType, self::$ecommerceTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid e-commerce type (%s)', $ecommerceType ) );
        }

        $this->ecommerceType = $ecommerceType;

        return $this;
    }

    /**
     * @return float
     */
    public function getShippingValue()
    {
        return $this->shippingValue;
    }

    /**
     * @param  float $shippingValue
     *
     * @return $this
     */
    public function setShippingValue( $shippingValue )
    {
        if (preg_match( '/^(?:\d*\.)?\d+$/', $shippingValue ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Shipping value should be a non-negative number (%s)',
                $shippingValue ) );
        }

        $this->shippingValue = (float)number_format( $shippingValue, 4, '.', '' );

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param  float $totalItems
     *
     * @return $this
     */
    public function setTotalItems( $totalItems )
    {
        if (preg_match( '/^(?:\d*\.)?\d+$/', $totalItems ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Items total value should be a non-negative number (%s)',
                $totalItems ) );
        }

        $this->totalItems = (float)number_format( $totalItems, 4, '.', '' );

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalOrder()
    {
        return $this->totalOrder;
    }

    /**
     * @param  float $totalOrder
     *
     * @return $this
     */
    public function setTotalOrder( $totalOrder )
    {
        if (preg_match( '/^(?:\d*\.)?\d+$/', $totalOrder ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Order total value should be a non-negative number (%s)',
                $totalOrder ) );
        }

        $this->totalOrder = (float)number_format( $totalOrder, 4, '.', '' );

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityInstallments()
    {
        return $this->quantityInstallments;
    }

    /**
     * @param  int $quantityInstallments
     *
     * @return $this
     */
    public function setQuantityInstallments( $quantityInstallments )
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
     * @return string
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * @param  string $deliveryTime
     *
     * @return $this
     */
    public function setDeliveryTime( $deliveryTime )
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityItems()
    {
        return $this->quantityItems;
    }

    /**
     * @param  int $quantityItems
     *
     * @return $this
     */
    public function setQuantityItems( $quantityItems )
    {
        if (preg_match( '/^\d+$/', $quantityItems ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Items quantity should be a non-negative integer (%s)',
                $quantityItems ) );
        }

        $this->quantityItems = intval( $quantityItems );

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityPaymentTypes()
    {
        return $this->quantityPaymentTypes;
    }

    /**
     * @param  int $quantityPaymentTypes
     *
     * @return $this
     */
    public function setQuantityPaymentTypes( $quantityPaymentTypes )
    {
        if (preg_match( '/^\d+$/', $quantityPaymentTypes ) !== 1) {
            throw new InvalidArgumentException( sprintf( 'Payment types quantity should be a non-negative integer (%s)',
                $quantityPaymentTypes ) );
        }

        $this->quantityPaymentTypes = intval( $quantityPaymentTypes );

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param  string $ip
     *
     * @return $this
     */
    public function setIp( $ip )
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param  string $notes
     *
     * @return $this
     */
    public function setNotes( $notes )
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param  int $status
     *
     * @return $this
     */
    public function setStatus( $status )
    {
        if (!in_array( intval( $status ), self::$statuses )) {
            throw new InvalidArgumentException( sprintf( 'Invalid status (%s)', $status ) );
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param  string $origin
     *
     * @return $this
     */
    public function setOrigin( $origin )
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReservationDate()
    {
        return $this->reservationDate;
    }

    /**
     * @param  \DateTime $reservationDate
     *
     * @return $this
     */
    public function setReservationDate( DateTime $reservationDate )
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData
     */
    public function getBillingData()
    {
        return $this->customerBillingData;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData $customerBillingData
     *
     * @return $this
     */
    public function setBillingData( CustomerBillingData $customerBillingData )
    {
        $this->customerBillingData = $customerBillingData;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData
     */
    public function getShippingData()
    {
        return $this->customerShippingData;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData $customerShippingData
     *
     * @return $this
     */
    public function setShippingData( CustomerShippingData $customerShippingData )
    {
        $this->customerShippingData = $customerShippingData;

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Payment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Payment[] $payments
     *
     * @return $this
     */
    public function setPayments( $payments )
    {
        foreach ($payments as $payment) {
            $this->addPayment( $payment );
        }

        return $this;
    }

    /**
     * @param  int $index
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Payment
     */
    public function getPayment( $index )
    {
        return $this->payments[ $index ];
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Item[] $items
     *
     * @return $this
     */
    public function setItems( $items )
    {
        foreach ($items as $item) {
            $this->addItem( $item );
        }

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Passenger[]
     */
    public function getPassengers()
    {
        return $this->passengers;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger[] $passengers
     *
     * @return $this
     */
    public function setPassengers( $passengers )
    {
        foreach ($passengers as $passenger) {
            $this->addPassenger( $passenger );
        }

        return $this;
    }

    /**
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Connection[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Connection[] $connections
     *
     * @return $this
     */
    public function setConnections( $connections )
    {
        foreach ($connections as $connection) {
            $this->addConnection( $connection );
        }

        return $this;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Payment $payment
     *
     * @return $this
     */
    public function addPayment( Payment $payment )
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Item $item
     *
     * @return $this
     */
    public function addItem( Item $item )
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Passenger $passenger
     *
     * @return $this
     */
    public function addPassenger( Passenger $passenger )
    {
        $this->passengers[] = $passenger;

        return $this;
    }

    /**
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Connection $connection
     *
     * @return $this
     */
    public function addConnection( Connection $connection )
    {
        $this->connections[] = $connection;

        return $this;
    }

    /**
     * @param  \XMLWriter $XMLWriter
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'ClearID_Input' );

        if ($this->fingerPrint) {
            $this->fingerPrint->toXML( $XMLWriter );
        } else {
            throw new RequiredFieldException( 'Field FingerPrint of the Order object is required' );
        }

        $XMLWriter->startElement( 'Pedido' );

        if ($this->id) {
            $XMLWriter->writeElement( 'PedidoID', $this->id );
        } else {
            throw new RequiredFieldException( 'Field ID of the Order object is required' );
        }

        if ($this->date) {
            $XMLWriter->writeElement( 'Data', $this->date->format( self::DATE_TIME_FORMAT ) );
        } else {
            throw new RequiredFieldException( 'Field Date of the Order object is required' );
        }

        if ($this->email) {
            $XMLWriter->writeElement( 'Email', $this->email );
        }

        if ($this->channel) {
            $XMLWriter->writeElement( 'CanalID', $this->channel );
        }

        if ($this->ecommerceType) {
            $XMLWriter->writeElement( 'B2B_B2C', $this->ecommerceType );
        }

        if ($this->shippingValue) {
            $XMLWriter->writeElement( 'ValorFrete', $this->shippingValue );
        }

        if (is_numeric( $this->totalItems )) {
            $XMLWriter->writeElement( 'ValorTotalItens', $this->totalItems );
        } else {
            throw new RequiredFieldException( 'Field TotalItems of the Order object is required' );
        }

        if (is_numeric( $this->totalOrder )) {
            $XMLWriter->writeElement( 'ValorTotalPedido', $this->totalOrder );
        } else {
            throw new RequiredFieldException( 'Field TotalOrder of the Order object is required' );
        }

        if ($this->quantityInstallments) {
            $XMLWriter->writeElement( 'QtdParcelas', $this->quantityInstallments );
        }

        if ($this->deliveryTime) {
            $XMLWriter->writeElement( 'PrazoEntrega', $this->deliveryTime );
        }

        if ($this->quantityItems) {
            $XMLWriter->writeElement( 'QtdItens', $this->quantityItems );
        }

        if ($this->quantityPaymentTypes) {
            $XMLWriter->writeElement( 'QtdFormasPagamento', $this->quantityPaymentTypes );
        }

        if ($this->ip) {
            $XMLWriter->writeElement( 'IP', $this->ip );
        }

        if ($this->notes) {
            $XMLWriter->writeElement( 'Observacao', $this->notes );
        }

        if ($this->status) {
            $XMLWriter->writeElement( 'Status', $this->status );
        }

        if ($this->origin) {
            $XMLWriter->writeElement( 'Origem', $this->origin );
        }

        if ($this->reservationDate) {
            $XMLWriter->writeElement( 'DataReserva', $this->reservationDate->format( self::DATE_TIME_FORMAT ) );
        }

        if ($this->customerBillingData) {
            $this->customerBillingData->toXML( $XMLWriter );
        } else {
            throw new RequiredFieldException( 'Field CustomerBillingData of the Order object is required' );
        }

        if ($this->customerShippingData) {
            $this->customerShippingData->toXML( $XMLWriter );
        } else {
            throw new RequiredFieldException( 'Field CustomerShippingData of the Order object is required' );
        }

        if (count( $this->payments ) > 0) {
            $XMLWriter->startElement( 'Pagamentos' );

            foreach ($this->payments as $payment) {
                $payment->toXML( $XMLWriter );
            }

            $XMLWriter->endElement();
        } else {
            throw new RequiredFieldException( 'Field Payments of the Order object is required' );
        }

        if (count( $this->items ) > 0) {
            $XMLWriter->startElement( 'Itens' );

            foreach ($this->items as $item) {
                $item->toXML( $XMLWriter );
            }

            $XMLWriter->endElement();
        } else {
            throw new RequiredFieldException( 'Field Items of the Order object is required' );
        }

        if (count( $this->passengers ) > 0) {
            $XMLWriter->startElement( 'Passageiros' );

            foreach ($this->passengers as $passenger) {
                $passenger->toXML( $XMLWriter );
            }

            $XMLWriter->endElement();
        }

        if (count( $this->connections ) > 0) {
            $XMLWriter->startElement( 'Conexoes' );

            foreach ($this->connections as $connection) {
                $connection->toXML( $XMLWriter );
            }

            $XMLWriter->endElement();
        }

        $XMLWriter->endElement(); // Pedido
        $XMLWriter->endElement(); // ClearID_Input
    }
}
