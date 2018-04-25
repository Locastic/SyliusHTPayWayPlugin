<?php

namespace Locastic\SyliusHTPayWayPlugin;

use Payum\Core\GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;

final class HTPayWayOffsiteGatewayFactory extends GatewayFactory
{
    const FACTORY_NAME = 'ht_payway_offsite';

    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'HT PayWay Offsite',

//            'payum.action.capture' => new CaptureAction(),
//            'payum.action.convert_payment' => new ConvertPaymentAction(),
//            'payum.action.status' => new StatusAction(),
        ]);


//        return [];
    }
}