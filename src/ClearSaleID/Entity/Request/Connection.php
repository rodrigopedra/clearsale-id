<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;
use XMLWriter;

class Connection implements XmlEntityInterface
{
    /** @var  string */
    private $company;

    /** @var  string */
    private $flightNumber;

    /** @var  \DateTime */
    private $flightDate;

    /** @var  string */
    private $class;

    /** @var  string */
    private $from;

    /** @var  string */
    private $to;

    /** @var  \DateTime */
    private $departureDate;

    /** @var  \DateTime */
    private $arrivalDate;

    /**
     * @param  string    $company
     * @param  string    $flightNumber
     * @param  \DateTime $flightDate
     * @param  string    $class
     * @param  string    $from
     * @param  string    $to
     * @param  \DateTime $departureDate
     * @param  \DateTime $arrivalDate
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\Connection
     */
    public static function create(
        $company,
        $flightNumber,
        DateTime $flightDate,
        $class,
        $from,
        $to,
        DateTime $departureDate,
        DateTime $arrivalDate
    ) {
        $instance = new self;

        $instance->setCompany( $company );
        $instance->setFlightNumber( $flightNumber );
        $instance->setFlightDate( $flightDate );
        $instance->setClass( $class );
        $instance->setFrom( $from );
        $instance->setTo( $to );
        $instance->setDepartureDate( $departureDate );
        $instance->setArrivalDate( $arrivalDate );

        return $instance;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param  string $company
     *
     * @return $this
     */
    public function setCompany( $company )
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    /**
     * @param  string $flightNumber
     *
     * @return $this
     */
    public function setFlightNumber( $flightNumber )
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFlightDate()
    {
        return $this->flightDate;
    }

    /**
     * @param  \DateTime $flightDate
     *
     * @return $this
     */
    public function setFlightDate( DateTime $flightDate )
    {
        $this->flightDate = $flightDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param  string $class
     *
     * @return $this
     */
    public function setClass( $class )
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  string $from
     *
     * @return $this
     */
    public function setFrom( $from )
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  string $to
     *
     * @return $this
     */
    public function setTo( $to )
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @param  \DateTime $departureDate
     *
     * @return $this
     */
    public function setDepartureDate( DateTime $departureDate )
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * @param  \DateTime $arrivalDate
     *
     * @return $this
     */
    public function setArrivalDate( DateTime $arrivalDate )
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * @param  \XMLWriter $XMLWriter
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'Conexao' );

        if ($this->company) {
            $XMLWriter->writeElement( 'Companhia', $this->company );
        } else {
            throw new RequiredFieldException( 'Field Company of the Connection object is required' );
        }

        if ($this->flightNumber) {
            $XMLWriter->writeElement( 'NumeroVoo', $this->flightNumber );
        } else {
            throw new RequiredFieldException( 'Field FlightNumber of the Connection object is required' );
        }

        if ($this->flightDate) {
            $XMLWriter->writeElement( 'DataVoo', $this->flightDate->format( self::DATE_TIME_FORMAT ) );
        } else {
            throw new RequiredFieldException( 'Field FlightDate of the Connection object is required' );
        }

        if ($this->class) {
            $XMLWriter->writeElement( 'Classe', $this->class );
        } else {
            throw new RequiredFieldException( 'Field Class of the Connection object is required' );
        }

        if ($this->from) {
            $XMLWriter->writeElement( 'Origem', $this->from );
        } else {
            throw new RequiredFieldException( 'Field FROM of the Connection object is required' );
        }

        if ($this->to) {
            $XMLWriter->writeElement( 'Destino', $this->to );
        } else {
            throw new RequiredFieldException( 'Field To of the Connection object is required' );
        }

        if ($this->departureDate) {
            $XMLWriter->writeElement( 'DataPartida', $this->departureDate->format( self::DATE_TIME_FORMAT ) );
        } else {
            throw new RequiredFieldException( 'Field DepartureDate of the Connection object is required' );
        }

        if ($this->arrivalDate) {
            $XMLWriter->writeElement( 'DataChegada', $this->arrivalDate->format( self::DATE_TIME_FORMAT ) );
        } else {
            throw new RequiredFieldException( 'Field ArrivalDate of the Connection object is required' );
        }

        $XMLWriter->endElement();
    }
}
