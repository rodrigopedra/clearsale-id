<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use XMLWriter;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class Phone implements XmlEntityInterface
{
    const NAO_DEFINIDO = 0;
    const RESIDENCIAL  = 1;
    const COMERCIAL    = 2;
    const RECADOS      = 3;
    const COBRANCA     = 4;
    const TEMPORARIO   = 5;
    const CELULAR      = 6;

    private static $types = [
        self::NAO_DEFINIDO,
        self::RESIDENCIAL,
        self::COMERCIAL,
        self::RECADOS,
        self::COBRANCA,
        self::TEMPORARIO,
        self::CELULAR,
    ];

    /** @var  int */
    private $type;

    /** @var  string */
    private $ddi;

    /** @var  string */
    private $ddd;

    /** @var  string */
    private $number;

    /** @var  string */
    private $extension;

    public static function create( $type, $ddd, $number )
    {
        $instance = new self();

        $instance->setType( $type );
        $instance->setDDD( $ddd );
        $instance->setNumber( $number );

        return $instance;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType( $type )
    {
        if (!in_array( intval( $type ), self::$types )) {
            throw new InvalidArgumentException( sprintf( 'Invalid type (%s)', $type ) );
        }

        $this->type = $type;

        return $this;
    }

    public function getDDI()
    {
        return $this->ddi;
    }

    public function setDDI( $ddi )
    {
        $ddi = preg_replace( '/^0+|\D/', '', $ddi );

        if (strlen( $ddi ) < 1 || strlen( $ddi ) > 3) {
            throw new InvalidArgumentException( sprintf( 'Invalid DDI (%s)', $ddi ) );
        }

        $this->ddi = $ddi;

        return $this;
    }

    public function getDDD()
    {
        return $this->ddd;
    }

    public function setDDD( $ddd )
    {
        $ddd = preg_replace( '/^0+|\D/', '', $ddd );

        if (strlen( $ddd ) !== 2) {
            throw new InvalidArgumentException( sprintf( 'Invalid DDD (%s)', $ddd ) );
        }

        $this->ddd = $ddd;

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber( $number )
    {
        $number = preg_replace( '/^0+|\D/', '', $number );

        if (strlen( $number ) !== 9 && strlen( $number ) !== 8) {
            throw new InvalidArgumentException( sprintf( 'Invalid Number (%s)', $number ) );
        }

        $this->number = $number;

        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension( $extension )
    {
        if (strlen( $extension ) > 5) {
            throw new InvalidArgumentException( sprintf( 'Extension should be no longer than 5 characters (%s)',
                $extension ) );
        }

        $this->extension = $extension;

        return $this;
    }

    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'Telefone' );

        if (!is_null( $this->type )) {
            $XMLWriter->writeElement( 'Tipo', $this->type );
        } else {
            throw new RequiredFieldException( 'Field Type of the Phone object is required' );
        }

        if ($this->ddi) {
            $XMLWriter->writeElement( 'DDI', $this->ddi );
        }

        if ($this->ddd) {
            $XMLWriter->writeElement( 'DDD', $this->ddd );
        } else {
            throw new RequiredFieldException( 'Field DDD of the Phone object is required' );
        }

        if ($this->number) {
            $XMLWriter->writeElement( 'Numero', $this->number );
        } else {
            throw new RequiredFieldException( 'Field Number of the Phone object is required' );
        }

        if ($this->extension) {
            $XMLWriter->writeElement( 'Ramal', $this->extension );
        }

        $XMLWriter->endElement();
    }
}
