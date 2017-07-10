<?php

namespace RodrigoPedra\ClearSaleID\Service;

use XMLWriter;
use RodrigoPedra\ClearSaleID\Entity\Response\PackageStatus;
use RodrigoPedra\ClearSaleID\Entity\Response\TransactionStatus;
use RodrigoPedra\ClearSaleID\Entity\Request\Order as OrderRequest;

class Integration
{
    /** @var Connector */
    protected $connector;

    /**
     * Construtor para gerar a integração com a ClearSale
     *
     * @param Connector $connector
     */
    public function __construct( Connector $connector )
    {
        $this->connector = $connector;
    }

    /**
     * Método para envio de um pedido
     *
     * @param OrderRequest $order
     *
     * @return PackageStatus
     */
    public function sendOrder( OrderRequest $order )
    {

        $function   = 'SubmitInfo';
        $parameters = [
            'entityCode' => $this->connector->getEntityCode(),
            'xmlDados'   => $this->createRequestOrderXML( $order ),
        ];

        $response = $this->connector->doRequest( $function, $parameters );

        $packageStatusResponse = new PackageStatus( $response->SubmitInfoResult );

        $this->connector->log( 'Integration@sendOrder', compact( 'packageStatusResponse' ) );

        return $packageStatusResponse;
    }

    /**
     * Retorna o status de um pedido
     *
     * @param string $orderId
     *
     * @return PackageStatus
     */
    public function checkOrderStatus( $orderId )
    {
        $function   = 'CheckOrderStatus';
        $parameters = [
            'entityCode'      => $this->connector->getEntityCode(),
            'pedidoIDCliente' => $orderId
        ];

        $response = $this->connector->doRequest( $function, $parameters );

        $packageStatusResponse = new PackageStatus( $response->CheckOrderStatusResult );

        $this->connector->log( 'Integration@checkOrderStatus', compact( 'packageStatusResponse' ) );

        return $packageStatusResponse;
    }

    /**
     * Método que atualiza o status do pedido para o status recebido no parametro statusPedido
     *
     * @param  string $orderId
     * @param  int    $newStatusId
     * @param  string $notes
     *
     * @return TransactionStatus
     */
    public function updateOrderStatus( $orderId, $newStatusId, $notes = '' )
    {
        $function   = 'UpdateOrderStatus';
        $parameters = [
            'entityCode'  => $this->connector->getEntityCode(),
            'orderId'     => $orderId,
            'newStatusId' => $newStatusId,
            'obs'         => substr( trim( $notes ), 0, 50 ),
        ];

        // false indicates this method uses extended endpoint
        $response = $this->connector->doRequest( $function, $parameters, false );

        $transactionStatusResponse = new TransactionStatus( $response->UpdateOrderStatusResult );

        $this->connector->log( 'Integration@updateOrderStatus', compact( 'transactionStatusResponse' ) );

        return $transactionStatusResponse;
    }

    private function createRequestOrderXML( OrderRequest $order )
    {
        $xmlWriter = new XMLWriter;

        $xmlWriter->openMemory();
        $xmlWriter->startDocument( '1.0', 'UTF-8' );

        $order->toXML( $xmlWriter );

        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory( true );
    }
}
