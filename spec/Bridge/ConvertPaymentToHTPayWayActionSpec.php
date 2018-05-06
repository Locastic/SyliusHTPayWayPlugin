<?php

namespace spec\Locastic\SyliusHTPayWayPlugin\Bridge;

use Locastic\SyliusHTPayWayPlugin\Bridge\ConvertPaymentToHTPayWayAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Request\Convert;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class ConvertPaymentToHTPayWayActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConvertPaymentToHTPayWayAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldHaveType(GatewayAwareInterface::class);
    }

    function it_executes(
        Convert $request,
        PaymentInterface $payment,
        OrderInterface $order,
        CustomerInterface $customer,
        AddressInterface $billingAddress
    ) {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $payment->getOrder()->willReturn($order);
        $payment->getDetails()->willReturn([]);

        $order->getTotal()->willReturn(1000);
        $order->getNumber()->willReturn(1000);
        $order->getCustomer()->willReturn($customer);
        $order->getBillingAddress()->willReturn($billingAddress);

        $request->setResult(
            [
                "pgwOrderId" => "1000",
                "pgwAmount" => 1000,
                "pgwEmail" => null,
                "pgwFirstName" => null,
                "pgwLastName" => null,
                "pgwStreet" => null,
                "pgwCity" => null,
                "pgwPostCode" => null,
                "pgwPhoneNumber" => null,
            ]
        )->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_covert_request(
        Convert $request,
        PaymentInterface $payment
    ): void {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $this->supports($request)->shouldReturn(true);
    }
}
