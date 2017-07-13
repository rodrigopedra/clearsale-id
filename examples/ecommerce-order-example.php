<?php

date_default_timezone_set( 'America/Sao_Paulo' );

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/logger.php';

use RodrigoPedra\ClearSaleID\Service\Analysis;
use RodrigoPedra\ClearSaleID\Service\Connector;
use RodrigoPedra\ClearSaleID\Environment\Sandbox;
use RodrigoPedra\ClearSaleID\Service\Integration;
use RodrigoPedra\ClearSaleID\Entity\Request\Item;
use RodrigoPedra\ClearSaleID\Entity\Request\Order;
use RodrigoPedra\ClearSaleID\Entity\Request\Phone;
use RodrigoPedra\ClearSaleID\Entity\Request\Address;
use RodrigoPedra\ClearSaleID\Entity\Request\Payment;
use RodrigoPedra\ClearSaleID\Entity\Request\FingerPrint;
use RodrigoPedra\ClearSaleID\Entity\Request\AbstractCustomer;
use RodrigoPedra\ClearSaleID\Entity\Request\CustomerBillingData;
use RodrigoPedra\ClearSaleID\Entity\Request\CustomerShippingData;

try {
    // Dados da Integração com a ClearSale
    $entityCode = '<CLEARSALE_ENTITY_CODE>';

    // ambiente
    $environment = new Sandbox( $entityCode, new ExampleLogger );
    $environment->setDebug( true );

    // serviços
    $connector   = new Connector( $environment );
    $integration = new Integration( $connector );
    $clearSale   = new Analysis( $integration );

    // Dados do Pedido
    $fingerPrint          = new FingerPrint( createSessionId() );
    $orderId              = createOrderId();
    $date                 = new \DateTime();
    $email                = 'cliente@clearsale.com.br';
    $totalItems           = 10.0;
    $totalOrder           = 17.5;
    $quantityInstallments = 1;
    $ip                   = '127.0.0.1';
    $origin               = 'WEB';
    $customerBillingData  = createCustomerBillingData();
    $customerShippingData = createCustomerShippingData();
    $item                 = Item::create( 1, 'Adaptador USB', 10.0, 1 );
    $payment              = Payment::create( Payment::BOLETO_BANCARIO, new \DateTime(), 17.5 );

    // Criar Pedido
    $order = Order::createEcommerceOrder(
        $fingerPrint,
        $orderId,
        $date,
        $email,
        $totalItems,
        $totalOrder,
        $quantityInstallments,
        $ip,
        $origin,
        $customerBillingData,
        $customerShippingData,
        $payment,
        $item
    );

    $response = $clearSale->analysis( $order );

    // Resultado da análise
    switch ($response) {
        case Analysis::ORDER_STATUS_APPROVED:
            // Análise aprovou a cobrança, realizar o pagamento
            echo 'Aprovado' . PHP_EOL;

            break;
        case Analysis::ORDER_STATUS_REJECTED:
            // Análise não aprovou a cobrança
            echo 'Reprovado' . PHP_EOL;

            break;
        default:
            // Análise pendente de aprovação manual
            echo 'Erro' . PHP_EOL;
    }

    if ($clearSale->updateOrderStatus( $orderId, Analysis::UPDATE_ORDER_STATUS_ORDER_APPROVED ) === true) {
        echo 'Status do pedido atualizado';
    }
} catch ( Exception $e ) {
    echo 'ERRO', PHP_EOL, PHP_EOL;

    // Erro genérico da análise
    echo $e->getMessage();
}

function createOrderId()
{
    return sprintf( 'TEST-%s', createSessionId() );
}

function createSessionId()
{
    return md5( uniqid( rand(), true ) );
}

function createCustomerBillingData()
{
    $id            = '1';
    $legalDocument = '63165236372';
    $name          = 'Fulano da Silva';
    $address       = createAddress();
    $phone         = Phone::create( Phone::COMERCIAL, '11', '37288788' );
    $birthDate     = new \DateTime( '1980-01-01' );

    return CustomerBillingData::create(
        $id,
        AbstractCustomer::TYPE_PESSOA_FISICA,
        $legalDocument,
        $name,
        $address,
        $phone,
        $birthDate
    );
}

function createCustomerShippingData()
{
    $id            = '1';
    $legalDocument = '63165236372';
    $name          = 'Fulano da Silva';
    $address       = createAddress();
    $phone         = Phone::create( Phone::COMERCIAL, '11', '37288788' );

    return CustomerShippingData::create(
        $id,
        AbstractCustomer::TYPE_PESSOA_FISICA,
        $legalDocument,
        $name,
        $address,
        $phone
    );
}

function createAddress()
{
    $street  = 'Rua José de Oliveira Coutinho';
    $number  = 151;
    $county  = 'Barra Funda';
    $country = 'Brasil';
    $city    = 'São Paulo';
    $state   = 'SP';
    $zip     = '01144020';

    return Address::create( $street, $number, $county, $country, $city, $state, $zip );
}

exit;
