<?php

namespace RodrigoPedra\ClearSaleID\Test\Entity\Request;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Request\Fixtures\PhoneFixture;
use RodrigoPedra\ClearSaleID\Entity\Request\Phone;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;

class PhoneTest extends TestCase
{
    /** @var  Phone */
    private $phone;

    protected function setUp()
    {
        $this->phone = PhoneFixture::createPhone();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->phone = null;
    }

    /** @test */
    public function testPhone()
    {
        $this->assertSame( Phone::COMERCIAL, $this->phone->getType() );
        $this->assertSame( '11', $this->phone->getDDD() );
        $this->assertSame( '37288788', $this->phone->getNumber() );
    }

    /** @test */
    public function testPhoneToXml()
    {
        $outputXML       = $this->generateXML( $this->phone );
        $expectedXmlFile = __DIR__ . '/../../../data/phone.xml';

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
