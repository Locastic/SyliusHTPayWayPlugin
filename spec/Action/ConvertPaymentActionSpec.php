<?php

namespace spec\Locastic\SyliusHTPayWayPlugin\Action;

use Locastic\SyliusHTPayWayPlugin\Action\ConvertPaymentAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use PhpSpec\ObjectBehavior;

class ConvertPaymentActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_executes(
        Convert $request,
        PaymentInterface $payment
    ): void {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $payment->getDetails()->willReturn([]);
        $payment->getNumber()->willReturn(1);
        $payment->getTotalAmount()->willReturn(1);
        $payment->getClientEmail()->willReturn('john@locastic.com');

        $request->setResult(["pgwOrderId" => 1, "pgwAmount" => 1, "pgwEmail" => "john@locastic.com"])->shouldBeCalled();

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
