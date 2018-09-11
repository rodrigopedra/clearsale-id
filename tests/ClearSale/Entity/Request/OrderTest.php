<?php

namespace RodrigoPedra\ClearSaleID\Test\Entity\Request;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Request\Fixtures\OrderFixture;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class OrderTest extends TestCase
{
    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Order */
    private $ecommerceOrder;

    protected function setUp()
    {
        $this->ecommerceOrder = OrderFixture::createEcommerceOrder();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->ecommerceOrder = null;
    }

    /** @test */
    public function testEcommerceOrderToXml()
    {
        $outputXML       = $this->generateXML( $this->ecommerceOrder );
        $expectedXmlFile = __DIR__ . '/../../../data/order-ecommerce.xml';

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
