<?php

namespace RodrigoPedra\ClearSaleID\Service;

use RodrigoPedra\ClearSaleID\Entity\Request\Order as OrderRequest;
use RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus;
use RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus;

class Integration
{
    /** @var  \RodrigoPedra\ClearSaleID\Service\Connector */
    protected $connector;

    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Método para envio de um pedido
     *
     * @param  \RodrigoPedra\ClearSaleID\Entity\Request\Order  $order
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus
     * @throws \SoapFault
     */
    public function sendOrder(OrderRequest $order): PackageStatus
    {
        $function = 'SubmitInfo';
        $parameters = [
            'entityCode' => $this->connector->getEntityCode(),
            'xmlDados' => $this->createRequestOrderXML($order),
        ];

        $response = $this->connector->doRequest($function, $parameters);

        $packageStatusResponse = new PackageStatus($response->SubmitInfoResult);

        $this->connector->log('Integration@sendOrder', \compact('packageStatusResponse'));

        return $packageStatusResponse;
    }

    /**
     * Retorna o status de um pedido
     *
     * @param  string  $orderId
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus
     * @throws \SoapFault
     */
    public function checkOrderStatus(string $orderId): PackageStatus
    {
        $function = 'CheckOrderStatus';
        $parameters = [
            'entityCode' => $this->connector->getEntityCode(),
            'pedidoIDCliente' => $orderId,
        ];

        $response = $this->connector->doRequest($function, $parameters);

        $packageStatusResponse = new PackageStatus($response->CheckOrderStatusResult);

        $this->connector->log('Integration@checkOrderStatus', \compact('packageStatusResponse'));

        return $packageStatusResponse;
    }

    /**
     * Método que atualiza o status do pedido para o status recebido no parâmetro statusPedido
     *
     * @param  string  $orderId
     * @param  int  $newStatusId
     * @param  string  $notes
     * @return \RodrigoPedra\ClearSaleID\Entity\Response\UpdateOrderStatus
     * @throws \SoapFault
     */
    public function updateOrderStatus(string $orderId, int $newStatusId, string $notes = ''): UpdateOrderStatus
    {
        $function = 'UpdateOrderStatus';
        $parameters = [
            'entityCode' => $this->connector->getEntityCode(),
            'orderId' => $orderId,
            'newStatusId' => $newStatusId,
            'obs' => \substr(\trim($notes), 0, 50),
        ];

        // false indicates this method uses extended endpoint
        $response = $this->connector->doRequest($function, $parameters, false);

        $updateOrderStatusResponse = new UpdateOrderStatus($response->UpdateOrderStatusResult);

        $this->connector->log('Integration@updateOrderStatus', \compact('updateOrderStatusResponse'));

        return $updateOrderStatusResponse;
    }

    private function createRequestOrderXML(OrderRequest $order): string
    {
        $xmlWriter = new \XMLWriter();

        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');

        $order->toXML($xmlWriter);

        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }
}
