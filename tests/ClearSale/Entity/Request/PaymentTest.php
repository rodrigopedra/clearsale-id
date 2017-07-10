<?php

namespace RodrigoPedra\ClearSaleID\Test\Entity\Request;

use DateTime;
use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Request\Payment;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\ClearSaleID\Entity\Request\Fixtures\PaymentFixture;

class PaymentTest extends TestCase
{
    /** @var  Payment */
    private $payment;

    protected function setUp()
    {
        $this->payment = PaymentFixture::createPayment();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->payment = null;
    }

    /** @test  */
    public function testPayment()
    {
        $this->assertInstanceOf( DateTime::class, $this->payment->getDate() );
        $this->assertSame( 17.5, $this->payment->getAmount() );
        $this->assertSame( Payment::BOLETO_BANCARIO, $this->payment->getType() );
    }

    /** @test  */
    public function testPaymentToXml()
    {
        $outputXML       = $this->generateXML( $this->payment );
        $expectedXmlFile = __DIR__ . '/../../../data/payment.xml';

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
