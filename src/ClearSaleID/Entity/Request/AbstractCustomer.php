<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use XMLWriter;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

abstract class AbstractCustomer implements XmlEntityInterface
{
    const TYPE_PESSOA_FISICA   = 1;
    const TYPE_PESSOA_JURIDICA = 2;

    protected static $customerTypes = [
        self::TYPE_PESSOA_FISICA,
        self::TYPE_PESSOA_JURIDICA,
    ];

    const SEX_MASCULINE = 'M';
    const SEX_FEMININE  = 'F';

    protected static $sexTypes = [
        self::SEX_MASCULINE,
        self::SEX_FEMININE,
    ];

    /** @var  string */
    protected $id;

    /** @var  int */
    protected $type;

    /** @var  string */
    protected $legalDocument1;

    /** @var  string */
    protected $legalDocument2;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $email;

    /** @var  string */
    protected $sex;

    /** @var  DateTime */
    protected $birthDate;

    /** @var  Address */
    protected $address;

    /** @var  Phone[] */
    protected $phones;

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $id
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setId( $id )
    {
        if (empty( $id )) {
            throw new InvalidArgumentException( 'The id value is empty!' );
        }

        $this->id = $id;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param string $type
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setType( $type )
    {
        if (!in_array( $type, self::$customerTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid type (%s)', $type ) );
        }

        $this->type = $type;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLegalDocument1()
    {
        return $this->legalDocument1;
    }

    /**
     *
     * @param string $legalDocument1
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setLegalDocument1( $legalDocument1 )
    {
        $legalDocument1 = preg_replace( '/\D/', '', $legalDocument1 );

        if (empty( $legalDocument1 )) {
            throw new InvalidArgumentException( 'LegalDocument1 is empty!' );
        }

        $this->legalDocument1 = $legalDocument1;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLegalDocument2()
    {
        return $this->legalDocument2;
    }

    /**
     *
     * @param string $legalDocument2
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setLegalDocument2( $legalDocument2 )
    {
        $legalDocument2 = preg_replace( '/\D/', '', $legalDocument2 );

        if (empty( $legalDocument2 )) {
            throw new InvalidArgumentException( 'LegalDocument2 is empty!' );
        }

        $this->legalDocument2 = $legalDocument2;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setName( $name )
    {
        if (empty( $name )) {
            throw new InvalidArgumentException( 'Name is empty!' );
        }

        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param string $email
     *
     * @return AbstractCustomer
     */
    public function setEmail( $email )
    {
        $this->email = $email;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     *
     * @param string $sex
     *
     * @return AbstractCustomer
     * @throws InvalidArgumentException
     */
    public function setSex( $sex )
    {
        if (!in_array( $sex, self::$sexTypes )) {
            throw new InvalidArgumentException( sprintf( 'Invalid sex (%s)', $sex ) );
        }

        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     *
     * @param DateTime $birthDate
     *
     * @return AbstractCustomer
     */
    public function setBirthDate( DateTime $birthDate )
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *
     * @param Address $address
     *
     * @return AbstractCustomer
     */
    public function setAddress( Address $address )
    {
        $this->address = $address;

        return $this;
    }

    /**
     *
     * @return Phone[]
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     *
     * @param Phone|Phone[] $phones
     *
     * @return AbstractCustomer
     */
    public function setPhones( $phones )
    {
        foreach ((array)$phones as $phone) {
            $this->addPhone( $phone );
        }

        return $this;
    }

    /**
     *
     * @param Phone $phone
     *
     * @return AbstractCustomer
     */
    public function addPhone( Phone $phone )
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     *
     * @param XMLWriter $XMLWriter
     *
     * @throws RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        if ($this->id) {
            $XMLWriter->writeElement( 'UsuarioID', $this->id );
        } else {
            throw new RequiredFieldException( 'Field ID of the Customer object is required' );
        }

        if ($this->type) {
            $XMLWriter->writeElement( 'TipoUsuario', $this->type );
        } else {
            throw new RequiredFieldException( 'Field Type of the Customer object is required' );
        }

        if ($this->legalDocument1) {
            $XMLWriter->writeElement( 'DocumentoLegal1', $this->legalDocument1 );
        } else {
            throw new RequiredFieldException( 'Field LegalDocument1 of the Customer object is required' );
        }

        if ($this->legalDocument2) {
            $XMLWriter->writeElement( 'DocumentoLegal2', $this->legalDocument2 );
        }

        if ($this->name) {
            $XMLWriter->writeElement( 'Nome', $this->name );
        } else {
            throw new RequiredFieldException( 'Field name of the Customer object is required' );
        }

        if ($this->email) {
            $XMLWriter->writeElement( 'Email', $this->email );
        }

        if ($this->sex) {
            $XMLWriter->writeElement( 'Sexo', $this->sex );
        }

        if ($this->birthDate) {
            $XMLWriter->writeElement( 'Nascimento', $this->birthDate->format( self::DATE_TIME_FORMAT ) );
        }

        if ($this->address) {
            $this->address->toXML( $XMLWriter );
        } else {
            throw new RequiredFieldException( 'Field Address of the Customer object is required' );
        }

        if (count( $this->phones ) > 0) {
            $XMLWriter->startElement( 'Telefones' );

            foreach ($this->phones as $phone) {
                $phone->toXML( $XMLWriter );
            }

            $XMLWriter->endElement();
        } else {
            throw new RequiredFieldException( 'Field Phones of the Customer object is required' );
        }
    }
}
