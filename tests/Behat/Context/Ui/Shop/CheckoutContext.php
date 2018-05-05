<?php

namespace Tests\Locastic\SyliusHTPayWayPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePage;
use Tests\Locastic\SyliusHTPayWayPlugin\Behat\Page\External\HTPayWayPaymentPage;


final class CheckoutContext implements Context
{
    private $completePage;

    private $paymentPage;

    public function __construct(
        CompletePage $completePage,
        HTPayWayPaymentPage $paymentPage
    ) {
        $this->completePage = $completePage;
        $this->paymentPage = $paymentPage;
    }

    /**
     * @When I confirm my order with HT PayWay Offsite payment
     */
    public function iConfirmMyOrderWithHtPaywayOffsitePayment()
    {
        $this->completePage->confirmOrder();
    }

    /**
     * @When I payed successfully in to HT PayWay Offsite gateway
     */
    public function iPayedSuccessfullyInToHtPaywayOffsiteGateway()
    {
        $this->paymentPage->paySuccessfully();
    }

    /**
     * @When I cancel my HT PayWay Offsite payment
     */
    public function iCancelMyHtPaywayOffsitePayment()
    {
        $this->paymentPage->payCanceled();
    }
}
