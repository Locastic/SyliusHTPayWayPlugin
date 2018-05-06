<?php

namespace spec\Locastic\SyliusHTPayWayPlugin;

use Locastic\SyliusHTPayWayPlugin\HTPayWayOffsiteGatewayFactory;
use Payum\Core\GatewayFactory;
use PhpSpec\ObjectBehavior;

class HTPayWayOffsiteGatewayFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HTPayWayOffsiteGatewayFactory::class);
        $this->shouldHaveType(GatewayFactory::class);
    }

    function it_populateConfig_run(): void
    {
        $this->createConfig([])->shouldBeArray();
    }
}
