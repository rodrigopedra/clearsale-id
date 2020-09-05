<?php

namespace RodrigoPedra\Tests\ClearSaleID\Environment;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Environment\Production;

class ProductionTest extends TestCase
{
    const ENTITY_CODE = 'ENTITY-CODE';
    const REGULAR_WEBSERVICE_URL = 'https://integracao.clearsale.com.br/service.asmx';
    const EXTENDED_WEBSERICE_URL = 'https://integracao.clearsale.com.br/ExtendedService.asmx';

    /** @var  \RodrigoPedra\ClearSaleID\Environment\AbstractEnvironment */
    private $environment;

    protected function setUp()
    {
        $this->environment = new Production(self::ENTITY_CODE);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->environment = null;
    }

    /** @test */
    public function testEntityCode()
    {
        $this->assertSame(self::ENTITY_CODE, $this->environment->getEntityCode());
    }

    /** @test */
    public function testRegularWebServiceUrl()
    {
        $this->assertSame(self::REGULAR_WEBSERVICE_URL, $this->environment->getRegularEndpoint());
    }

    /** @test */
    public function testExtendedWebServiceUrl()
    {
        $this->assertSame(self::EXTENDED_WEBSERICE_URL, $this->environment->getExtendedEndpoint());
    }

    /** @test */
    public function testDebug()
    {
        $this->assertFalse($this->environment->isDebug());
    }

    /** @test */
    public function testDebugTrue()
    {
        $this->environment->setDebug(true);

        $this->assertTrue($this->environment->isDebug());
    }
}
