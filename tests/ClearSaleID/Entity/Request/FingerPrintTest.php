<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures\FingerPrintFixture;

class FingerPrintTest extends TestCase
{
    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint */
    private $fingerPrint;

    protected function setUp()
    {
        $this->fingerPrint = FingerPrintFixture::createFingerPrint();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->fingerPrint = null;
    }

    /** @test */
    public function testFingerPrint()
    {
        $this->assertSame('session-id-1234', $this->fingerPrint->getSessionId());
    }

    /** @test */
    public function testFingerPrintToXml()
    {
        $outputXML = $this->generateXML($this->fingerPrint);
        $expectedXmlFile = __DIR__ . '/../../../data/fingerprint.xml';

        $this->assertXmlStringEqualsXmlFile($expectedXmlFile, $outputXML);
    }

    private function generateXML(XmlEntityInterface $xmlEntity)
    {
        $xmlWriter = new \XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(false);

        $xmlEntity->toXML($xmlWriter);

        return $xmlWriter->outputMemory(true);
    }
}
