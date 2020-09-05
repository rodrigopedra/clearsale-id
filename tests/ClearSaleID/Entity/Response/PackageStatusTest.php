<?php

namespace RodrigoPedra\Tests\ClearSaleID\Entity\Response;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Response\Order;
use RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus;

class PackageStatusTest extends TestCase
{
    private $packageStatusResponse;
    private $orderResponse;

    protected function tearDown()
    {
        parent::tearDown();

        $this->packageStatusResponse = null;
        $this->orderResponse = null;
    }

    /**
     * @test
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     */
    public function testCreateFromXML()
    {
        $responseXmlFile = __DIR__ . '/../../../data/package-status.xml';
        $xml = \file_get_contents($responseXmlFile);

        $this->packageStatusResponse = new PackageStatus($xml);

        $this->assertSame('55929546-3c1e-4be0-b561-03ab72e74b32', $this->packageStatusResponse->getTransactionId());
        $this->assertSame(PackageStatus::STATUS_CODE_SUCCESS, $this->packageStatusResponse->getStatusCode());
        $this->assertSame('OK', $this->packageStatusResponse->getMessage());
    }

    /**
     * @test
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     */
    public function testOrderResponse()
    {
        $responseXmlFile = __DIR__ . '/../../../data/package-status.xml';
        $xml = \file_get_contents($responseXmlFile);

        $this->packageStatusResponse = new PackageStatus($xml);
        $this->orderResponse = $this->packageStatusResponse->getOrder();

        $this->assertSame('TEST-0A44444E6CEA465E85737421E651C70B', $this->orderResponse->getId());
        $this->assertSame(Order::STATUS_APPROVED_AUTOMATICALLY, $this->orderResponse->getStatus());
        $this->assertSame(79.94, $this->orderResponse->getScore());
        $this->assertEmpty($this->orderResponse->getQuizURL());
    }
}
