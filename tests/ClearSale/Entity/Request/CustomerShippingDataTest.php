<?php

namespace RodrigoPedra\ClearSaleID\Test\Entity\Request;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Request\Phone;
use RodrigoPedra\ClearSaleID\Entity\Request\Address;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Entity\Request\AbstractCustomer;
use RodrigoPedra\ClearSaleID\Entity\Request\Fixtures\CustomerShippingFixture;

class CustomerShippingDataTest extends TestCase
{
    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData */
    private $customer;

    protected function setUp()
    {
        $this->customer = CustomerShippingFixture::createCustomerShippingData();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->customer = null;
    }

    /** @test  */
    public function testCustomerShippingData()
    {
        $phones = $this->customer->getPhones();
        $phone  = $phones[ 0 ];

        $this->assertSame( '1', $this->customer->getId() );
        $this->assertSame( AbstractCustomer::TYPE_PESSOA_FISICA, $this->customer->getType() );
        $this->assertSame( 'Fulano da Silva', $this->customer->getName() );
        $this->assertInstanceOf( Address::class, $this->customer->getAddress() );
        $this->assertInstanceOf( Phone::class, $phone );
    }

    /** @test  */
    public function testCustomerShippingDataToXml()
    {
        $outputXML       = $this->generateXML( $this->customer );
        $expectedXmlFile = __DIR__ . '/../../../data/customer-shipping-data.xml';

        $this->assertXmlStringEqualsXmlFile( $expectedXmlFile, $outputXML );
    }

    private function generateXML( XmlEntityInterface $xmlEntity )
    {
        $xmlWriter = new \XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent( false );

        $xmlEntity->toXML( $xmlWriter );

        return $xmlWriter->outputMemory( true );
    }
}
