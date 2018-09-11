<?php

namespace RodrigoPedra\ClearSaleID\Service;

use Exception;
use InvalidArgumentException;
use RodrigoPedra\ClearSaleID\Entity\Request\Order as OrderRequest;

class Analysis
{
    const ORDER_STATUS_APPROVED          = 'Aprovado';
    const ORDER_STATUS_REJECTED          = 'Reprovado';
    const ORDER_STATUS_NO_ORDER_RETURNED = 'Erro';
    const ORDER_STATUS_INVALID           = 'Erro';

    const UPDATE_ORDER_STATUS_ORDER_APPROVED = 26;
    const UPDATE_ORDER_STATUS_ORDER_REJECTED = 27;

    private static $updateOrderStatusList = [
        self::UPDATE_ORDER_STATUS_ORDER_APPROVED,
        self::UPDATE_ORDER_STATUS_ORDER_REJECTED,
    ];

    /** @var  \RodrigoPedra\ClearSaleID\Service\Integration */
    private $integration;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus */
    private $packageStatusResponse;

    /** @var  \RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus */
    private $updateOrderStatusResponse;

    /**
     * Construtor para gerar a integração com a ClearSale
     *
     * @param  \RodrigoPedra\ClearSaleID\Service\Integration $integration
     */
    public function __construct( Integration $integration )
    {
        $this->integration = $integration;
    }

    /**
     * Método para envio de pedidos e retorno do status
     *
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Order $order
     *
     * @return string
     * @throws \Exception
     */
    public function analysis( OrderRequest $order )
    {
        $packageStatus = $this->integration->sendOrder( $order );

        if (!$packageStatus->isSuccessful()) {
            throw new Exception( sprintf( 'Transaction Failed! (statusCode: %s)', $packageStatus->getStatusCode() ) );
        }

        return $this->checkOrderStatus( $order->getId() );
    }

    /**
     * Retorna o status de aprovação de um pedido
     *
     * @param  string $orderId
     *
     * @return string
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     */
    public function checkOrderStatus( $orderId )
    {
        $this->packageStatusResponse = $this->integration->checkOrderStatus( $orderId );

        if (!$this->packageStatusResponse->getOrder()) {
            // no order returned
            return self::ORDER_STATUS_NO_ORDER_RETURNED;
        }

        if ($this->packageStatusResponse->getOrder()->isApproved()) {
            return self::ORDER_STATUS_APPROVED;
        }

        if ($this->packageStatusResponse->getOrder()->isRejected()) {
            return self::ORDER_STATUS_REJECTED;
        }

        // invalid order status
        return self::ORDER_STATUS_INVALID;
    }

    /**
     * Método para atualizar o pedido com o status do pagamento
     *
     * @param  string $orderId
     * @param  string $newStatusCode
     * @param  string $notes
     *
     * @return boolean
     * @throws \RodrigoPedra\ClearSaleID\Exception\UnexpectedErrorException
     * @throws \RodrigoPedra\ClearSaleID\Exception\UpdateOrderStatusException
     */
    public function updateOrderStatus( $orderId, $newStatusCode, $notes = '' )
    {
        if (!in_array( $newStatusCode, self::$updateOrderStatusList )) {
            throw new InvalidArgumentException( sprintf( 'Invalid new status code (%s)', $newStatusCode ) );
        }

        $this->updateOrderStatusResponse = $this->integration->updateOrderStatus( $orderId, $newStatusCode, $notes );

        return true;
    }

    /**
     * Retorna os detalhes do pedido após o pedido de análise
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus
     */
    public function getPackageStatus()
    {
        return $this->packageStatusResponse;
    }

    /**
     * Retorna os detalhes do pedido após o pedido de análise
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus
     */
    public function getUpdateOrderStatus()
    {
        return $this->updateOrderStatusResponse;
    }
}
