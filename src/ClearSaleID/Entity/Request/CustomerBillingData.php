<?php

namespace RodrigoPedra\ClearSaleID\Entity\Request;

use DateTime;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use XMLWriter;

class CustomerBillingData extends AbstractCustomer implements XmlEntityInterface
{
    /**
     * @param  string                                           $id
     * @param  string                                           $type
     * @param  string                                           $legalDocument
     * @param  string                                           $name
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Address $address
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Phone   $phone
     * @param  \DateTime                                        $birthDate
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData
     */
    public static function create(
        $id,
        $type,
        $legalDocument,
        $name,
        Address $address,
        $phone,
        DateTime $birthDate = null
    ) {
        $instance = new self;

        $instance->setId( $id );
        $instance->setType( $type );
        $instance->setLegalDocument1( $legalDocument );
        $instance->setName( $name );
        $instance->setAddress( $address );
        $instance->addPhone( $phone );

        if (!empty( $birthDate )) {
            $instance->setBirthDate( $birthDate );
        }

        return $instance;
    }

    /**
     * @param  \XMLWriter $XMLWriter
     *
     * @throws \RodrigoPedra\ClearSaleID\Exception\RequiredFieldException
     */
    public function toXML( XMLWriter $XMLWriter )
    {
        $XMLWriter->startElement( 'DadosCobranca' );

        parent::toXML( $XMLWriter );

        $XMLWriter->endElement();
    }
}
