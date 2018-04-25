<?php

namespace Locastic\SyliusHTPayWayPlugin;

use Locastic\SyliusHTPayWayPlugin\Action\CaptureOffsiteAction;
use Locastic\SyliusHTPayWayPlugin\Action\ConvertPaymentAction;
use Locastic\SyliusHTPayWayPlugin\Action\StatusAction;
use Payum\Core\GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;
use Locastic\TcomPayWay\AuthorizeForm\Model\Payment as PaymentOffsite;

final class HTPayWayOffsiteGatewayFactory extends GatewayFactory
{
    const FACTORY_NAME = 'ht_payway_offsite';

    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults(
            [
                'payum.tcompayway.template.capture' => '@LocasticTcomPayWayOffsite/capture.html.twig',

                'payum.factory_name' => self::FACTORY_NAME,
                'payum.factory_title' => 'HT PayWay Offsite',

                'payum.action.capture' => function (ArrayObject $config) {
                    return new CaptureOffsiteAction($config['payum.tcompayway.template.capture']);
                },
                'payum.action.status' => new StatusAction(),
                'payum.action.fill_order_details' => new ConvertPaymentAction(),
            ]
        );


        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'shop_id' => '',
                'secret_key' => '',
                'authorization_type' => '0',
                'sandbox' => true,
                'disable_installments' => '1',
            );

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = array(
                'shop_id',
                'secret_key',
            );

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $api = new PaymentOffsite(
                    $config['shop_id'],
                    $config['secret_key'],
                    null,
                    null,
                    $config['authorization_type'],
                    null,
                    null,
                    $config['sandbox']
                );

                if($config['disable_installments']) {
                    $api->setPgwDisableInstallments($config['disable_installments']);
                }

                return $api;
            };
        }
    }
}