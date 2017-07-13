<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use XMLWriter;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Address implements XmlEntityInterface
{
    /** @var  string */
    private $street;

    /** @var  string */
    private $number;

    /** @var  string */
    private $complement;

    /** @var  string */
    private $county;

    /** @var  string */
    private $city;

    /** @var  string */
    private $state;

    /** @var  string */
    private $country;

    /** @var  string */
    private $zipCode;

    public static function create( $street, $number, $county, $country, $city, $state, $zipCode, $complement = '' )
    {
        $instance = new self;

        $instance->setStreet( $street );
        $instance->setNumber( $number );
        $instance->setCounty( $county );
        $instance->setCountry( $country );
        $instance->setCity( $city );
        $instance->setState( $state );
        $instance->setZipCode( $zipCode );

        if (!empty( $complement )) {
            $instance->setComplement( $complement );
        }

        return $instance;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet( $street )
    {
        if (empty( $street )) {
            throw new InvalidArgumentException( 'Street is empty!' );
        }

        $this->street = $street;

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber( $number )
    {
        if (empty( $number )) {
            throw new InvalidArgumentException( 'Number is empty!' );
        }

        $this->number = (string)$number;

        return $this;
    }

    public function getComplement()
    {
        return $this->complement;
    }

    public function setComplement( $complement )
    {
        $this->complement = $complement;

        return $this;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function setCounty( $county )
    {
        if (empty( $county )) {
            throw new InvalidArgumentException( 'County is empty!' );
        }

        $this->county = $county;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity( $city )
    {
        if (empty( $city )) {
            throw new InvalidArgumentException( 'City is empty!' );
        }

        $this->city = $city;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState( $state )
    {
        if (empty( $state )) {
            throw new InvalidArgumentException( 'State is empty!' );
        }

        $this->state = $state;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry( $country )
    {
        $this->country = $country;

        return $this;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode( $zipCode )
    {
        $zipCode = preg_replace( '/\D/', '', $zipCode );

        if (empty( $zipCode )) {
            throw new InvalidArgumentException( 'ZipCode is empty!' );
        }

        $this->zipCode = $zipCode;

        return $this;
    }

    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'Endereco' );

        if ($this->street) {
            $XMLWriter->writeElement( 'Logradouro', $this->street );
        } else {
            throw new RequiredFieldException( 'Field Street of the Address object is required' );
        }

        if ($this->complement) {
            $XMLWriter->writeElement( 'Complemento', $this->complement );
        }

        if ($this->number) {
            $XMLWriter->writeElement( 'Numero', $this->number );
        } else {
            throw new RequiredFieldException( 'Field Number of the Address object is required' );
        }

        if ($this->county) {
            $XMLWriter->writeElement( 'Bairro', $this->county );
        } else {
            throw new RequiredFieldException( 'Field County of the Address object is required' );
        }

        if ($this->city) {
            $XMLWriter->writeElement( 'Cidade', $this->city );
        } else {
            throw new RequiredFieldException( 'Field City of the Address object is required' );
        }

        if ($this->state) {
            $XMLWriter->writeElement( 'UF', $this->state );
        } else {
            throw new RequiredFieldException( 'Field State of the Address object is required' );
        }

        if ($this->zipCode) {
            $XMLWriter->writeElement( 'CEP', $this->zipCode );
        } else {
            throw new RequiredFieldException( 'Field ZipCode of the Address object is required' );
        }

        if ($this->country) {
            $XMLWriter->writeElement( 'Pais', $this->country );
        } else {
            throw new RequiredFieldException( 'Field Country of the Address object is required' );
        }

        $XMLWriter->endElement();
    }
}
