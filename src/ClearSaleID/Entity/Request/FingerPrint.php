<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use XMLWriter;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;

class FingerPrint implements XmlEntityInterface
{
    /** @var  string */
    private $sessionId;

    public function __construct( $sessionId )
    {
        $this->sessionId = $sessionId;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setSessionId( $sessionId )
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function toXML( XMLWriter $XMLWriter )
    {
        if ($this->sessionId) {
            $XMLWriter->writeElement( 'SessionID', $this->sessionId );
        } else {
            throw new RequiredFieldException( 'Field SessionID of the FingerPrint object is required' );
        }
    }
}
