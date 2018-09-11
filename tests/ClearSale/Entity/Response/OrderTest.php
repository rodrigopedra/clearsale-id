<?php

namespace RodrigoPedra\ClearSaleID\Test\Entity\Response;

use PHPUnit\Framework\TestCase;
use RodrigoPedra\ClearSaleID\Entity\Response\Order as ResponseOrder;

class OrderTest extends TestCase
{
    private $responseOrder;

    protected function tearDown()
    {
        parent::tearDown();

        $this->responseOrder = null;
    }

    /** @test */
    public function testOrderResponseIdAndScore()
    {
        $id    = '123';
        $score = 43.100;

        $this->responseOrder = new ResponseOrder( $id, 43.100, ResponseOrder::STATUS_APPROVED_AUTOMATICALLY );

        $this->assertSame( $id, $this->responseOrder->getId() );
        $this->assertSame( $score, $this->responseOrder->getScore() );
    }

    /**
     * @test
     *
     * @dataProvider statusProvider
     *
     * @param $status
     * @param $expectedStatus
     */
    public function testOrderResponseStatus( $status, $expectedStatus )
    {
        $this->responseOrder = new ResponseOrder( '123', 43.100, $status );

        $this->assertSame( $expectedStatus, $this->responseOrder->getStatus() );
    }

    public function statusProvider()
    {
        return [
            [ 'APA', ResponseOrder::STATUS_APPROVED_AUTOMATICALLY ],
            [ 'RPP', ResponseOrder::STATUS_REJECTED_BY_POLITICS ],
            [ 'RPA', ResponseOrder::STATUS_REJECTED_AUTOMATICALLY ],
        ];
    }
}
