<?php

namespace Tests\Locastic\SyliusHTPayWayPlugin\Behat\Context\Ui\Shop;


use Behat\Behat\Context\Context;
use Tests\Locastic\SyliusHTPayWayPlugin\Behat\Mocker\HTPayWayOffsiteMocker;
use Sylius\Behat\Page\Shop\Checkout\CompletePage;

final class CheckoutContext implements Context
{
    private $HTPayWayOffsiteMocker;

    private $completePage;

    /**
     * CheckoutContext constructor.
     * @param HTPayWayOffsiteMocker $HTPayWayOffsiteMocker
     */
    public function __construct(HTPayWayOffsiteMocker $HTPayWayOffsiteMocker, CompletePage $completePage)
    {
        $this->HTPayWayOffsiteMocker = $HTPayWayOffsiteMocker;
        $this->completePage = $completePage;
    }

    /**
     * @When I confirm my order with HT PayWay Offsite payment
     */
    public function iConfirmMyOrderWithHtPaywayOffsitePayment()
    {
        $this->HTPayWayOffsiteMocker->mockApiCreatePayment(
            function () {
                $this->completePage->confirmOrder();
            }
        );
    }
}
