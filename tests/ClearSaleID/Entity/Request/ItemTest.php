<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Request;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\XmlEntityInterface;
use RodrigoPedra\Tests\ClearSaleID\Entity\Request\Fixtures\ItemFixture;

class ItemTest extends TestCase
{
    /** @var  \RodrigoPedra\ClearSaleID\Entity\Request\Item */
    private $item;

    protected function setUp()
    {
        $this->item = ItemFixture::createItem();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->item = null;
    }

    /** @test */
    public function testItem()
    {
        $this->assertSame('1', $this->item->getId());
        $this->assertSame('Adaptador USB', $this->item->getName());
        $this->assertSame(10.0, $this->item->getValue());
        $this->assertSame(1, $this->item->getQuantity());
    }

    /** @test */
    public function testItemToXml()
    {
        $outputXML = $this->generateXML($this->item);
        $expectedXmlFile = __DIR__ . '/../../../data/item.xml';

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
