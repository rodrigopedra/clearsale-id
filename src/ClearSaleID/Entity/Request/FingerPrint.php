<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Exception\RequiredFieldException;
use XMLWriter;

class FingerPrint implements XmlEntityInterface
{
    /** @var  string */
    private $sessionId;

    /**
     * FingerPrint constructor.
     *
     * @param  string $sessionId
     */
    public function __construct( $sessionId )
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param  string $sessionId
     *
     * @return $this
     */
    public function setSessionId( $sessionId )
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @param  \XMLWriter $XMLWriter
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        if ($this->sessionId) {
            $XMLWriter->writeElement( 'SessionID', $this->sessionId );
        } else {
            throw new RequiredFieldException( 'Field SessionID of the FingerPrint object is required' );
        }
    }
}
