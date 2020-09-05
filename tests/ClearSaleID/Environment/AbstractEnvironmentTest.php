<?php

namespace RodrigoPedra\Tests\ClearSaleID\Environment;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment;

class AbstractEnvironmentTest extends TestCase
{
    /** @var  AbstractEnvironment */
    private $environment;

    protected function setUp()
    {
        parent::setUp();

        $this->environment = $this->getMockBuilder(AbstractEnvironment::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityCode', 'getRegularEndpoint', 'getExtendedEndpoint', 'isDebug'])
            ->getMockForAbstractClass();

        $this->environment->expects($this->any())
            ->method('getEntityCode')
            ->will($this->returnValue('EC-123456'));

        $this->environment->expects($this->any())
            ->method('getRegularEndpoint')
            ->will($this->returnValue('http://localhost/service.asmx'));

        $this->environment->expects($this->any())
            ->method('getExtendedEndpoint')
            ->will($this->returnValue('http://localhost/extended.asmx'));

        $this->environment->expects($this->any())
            ->method('isDebug')
            ->will($this->returnValue(true));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->environment = null;
    }

    /** @test */
    public function testEntityCode()
    {
        $this->assertSame('EC-123456', $this->environment->getEntityCode());
    }

    /** @test */
    public function testWebServiceRegularUrl()
    {
        $this->assertSame('http://localhost/service.asmx', $this->environment->getRegularEndpoint());
    }

    /** @test */
    public function testWebServiceExtendedUrl()
    {
        $this->assertSame('http://localhost/extended.asmx', $this->environment->getExtendedEndpoint());
    }

    /** @test */
    public function testIsDebugTrue()
    {
        $this->assertTrue($this->environment->isDebug());
    }
}
