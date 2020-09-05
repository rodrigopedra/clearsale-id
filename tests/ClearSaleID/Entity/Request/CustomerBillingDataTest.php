<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request;

use DateTime;
use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Request\AbstractCustomer;
use RodrigoPedra\ClearSaleID\Entity\Request\Address;
use RodrigoPedra\ClearSaleID\Entity\Request\Phone;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures\CustomerBillingFixture;

class CustomerBillingDataTest extends TestCase
{
    /** @var  AbstractCustomer */
    private $customer;

    protected function setUp()
    {
        $this->customer = CustomerBillingFixture::createCustomerBillingData();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->customer = null;
    }

    /** @test */
    public function testCustomerBillingData()
    {
        $phones = $this->customer->getPhones();
        $phone = $phones[0];

        $this->assertSame('1', $this->customer->getId());
        $this->assertSame(AbstractCustomer::TYPE_PESSOA_FISICA, $this->customer->getType());
        $this->assertSame('Fulano da Silva', $this->customer->getName());
        $this->assertInstanceOf(Address::class, $this->customer->getAddress());
        $this->assertInstanceOf(Phone::class, $phone);
        $this->assertInstanceOf(DateTime::class, $this->customer->getBirthDate());
    }

    /** @test */
    public function testCustomerBillingDataToXml()
    {
        $outputXML = $this->generateXML($this->customer);
        $expectedXmlFile = __DIR__ . '/../../../data/customer-billing-data.xml';

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
