<?php

namespace RodrigoPedra\ClearSaleID\Service;

use RodrigoPedra\ClearSaleID\Entity\Request\Order as OrderRequest;
use RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus;
use RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus;
use RodrigoPedra\ClearSaleID\Exception\TransactionFailedException;

class Analysis
{
    public const ORDER_STATUS_APPROVED = 'Aprovado';
    public const ORDER_STATUS_REJECTED = 'Reprovado';
    public const ORDER_STATUS_NO_ORDER_RETURNED = 'Erro';
    public const ORDER_STATUS_INVALID = 'Erro';

    public const UPDATE_ORDER_STATUS_ORDER_APPROVED = 26;
    public const UPDATE_ORDER_STATUS_ORDER_REJECTED = 27;

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

    public function __construct(Integration $integration)
    {
        $this->integration = $integration;
    }

    /**
     * Método para envio de pedidos e retorno do status
     *
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Order  $order
     * @return string
     * @throws \SoapFault
     */
    public function analysis(OrderRequest $order): string
    {
        $packageStatus = $this->integration->sendOrder($order);

        if (! $packageStatus->isSuccessful()) {
            throw new TransactionFailedException(
                \sprintf('Transaction Failed! (statusCode: %s)', $packageStatus->getStatusCode())
            );
        }

        return $this->checkOrderStatus($order->getId());
    }

    /**
     * Retorna o status de aprovação de um pedido
     *
     * @param  string  $orderId
     * @return string
     * @throws \SoapFault
     */
    public function checkOrderStatus(string $orderId): string
    {
        $this->packageStatusResponse = $this->integration->checkOrderStatus($orderId);

        if (! $this->packageStatusResponse->getOrder()) {
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
     * @param  string  $orderId
     * @param  int  $newStatusCode
     * @param  string  $notes
     * @return boolean
     * @throws \SoapFault
     */
    public function updateOrderStatus(string $orderId, int $newStatusCode, string $notes = ''): bool
    {
        if (! \in_array($newStatusCode, self::$updateOrderStatusList)) {
            throw new \InvalidArgumentException(\sprintf('Invalid new status code (%s)', $newStatusCode));
        }

        $this->updateOrderStatusResponse = $this->integration->updateOrderStatus($orderId, $newStatusCode, $notes);

        return true;
    }

    /**
     * Retorna os detalhes do pedido após o pedido de análise
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus
     */
    public function getPackageStatus(): PackageStatus
    {
        return $this->packageStatusResponse;
    }

    /**
     * Retorna os detalhes do pedido após o pedido de análise
     *
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus
     */
    public function getUpdateOrderStatus(): UpdateOrderStatus
    {
        return $this->updateOrderStatusResponse;
    }
}
