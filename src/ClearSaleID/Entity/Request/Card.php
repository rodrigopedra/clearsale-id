<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use XMLWriter;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class Card implements XmlEntityInterface
{
    const DINERS           = 1;
    const MASTERCARD       = 2;
    const VISA             = 3;
    const OUTROS           = 4;
    const AMERICAN_EXPRESS = 5;
    const HIPERCARD        = 6;
    const AURA             = 7;

    private static $cards = [
        self::DINERS,
        self::MASTERCARD,
        self::VISA,
        self::OUTROS,
        self::AMERICAN_EXPRESS,
        self::HIPERCARD,
        self::AURA,
    ];

    /** @var  string */
    private $numberHash;

    /** @var  string */
    private $bin;

    /** @var  string */
    private $lastDigits;

    /** @var  string */
    private $type;

    /** @var  string */
    private $expirationDate;

    /** @var  string */
    private $name;

    public function getNumberHash()
    {
        return $this->numberHash;
    }

    public function setNumberHash( $numberHash )
    {
        $numberHash = trim( $numberHash );

        if (strlen( $numberHash ) > 40) {
            throw new InvalidArgumentException( sprintf( 'Card number hash should be no longer than 40 characters (%s)',
                $numberHash ) );
        }

        $this->numberHash = $numberHash;

        return $this;
    }

    public function getBin()
    {
        return $this->bin;
    }

    public function setBin( $bin )
    {
        $bin = trim( $bin );

        if (strlen( $bin ) !== 6) {
            throw new InvalidArgumentException( sprintf( 'Bin number should contain the first 6 characters in the card number (%s)',
                $bin ) );
        }

        $this->bin = $bin;

        return $this;
    }

    public function getLastDigits()
    {
        return $this->lastDigits;
    }

    public function setLastDigits( $lastDigits )
    {
        $lastDigits = trim( $lastDigits );

        if (strlen( $lastDigits ) !== 4) {
            throw new InvalidArgumentException( sprintf( 'Last digits should contain the last 4 characters in the card number (%s)',
                $lastDigits ) );
        }

        $this->lastDigits = $lastDigits;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType( $type )
    {
        if (!in_array( $type, self::$cards )) {
            throw new InvalidArgumentException( sprintf( 'Invalid type (%s)', $type ) );
        }

        $this->type = $type;

        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate( $expirationDate )
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    public function toXML( XMLWriter $XMLWriter )
    {
        if ($this->numberHash) {
            $XMLWriter->writeElement( 'HashNumeroCartao', $this->numberHash );
        }

        if ($this->bin) {
            $XMLWriter->writeElement( 'BinCartao', $this->bin );
        }

        if ($this->lastDigits) {
            $XMLWriter->writeElement( 'Cartao4Ultimos', $this->lastDigits );
        }

        if ($this->type) {
            $XMLWriter->writeElement( 'TipoCartao', $this->type );
        }

        if ($this->expirationDate) {
            $XMLWriter->writeElement( 'DataValidadeCartao', $this->expirationDate );
        }

        if ($this->name) {
            $XMLWriter->writeElement( 'NomeTitularCartao', $this->name );
        }
    }
}
