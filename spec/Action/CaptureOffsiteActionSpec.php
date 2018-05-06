<?php

namespace spec\Locastic\SyliusHTPayWayPlugin\Action;

use Locastic\SyliusHTPayWayPlugin\Action\CaptureOffsiteAction;
use Locastic\TcomPayWay\AuthorizeForm\Model\Payment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Security\TokenInterface;
use PhpSpec\ObjectBehavior;


class CaptureOffsiteActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CaptureOffsiteAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldHaveType(GatewayAwareInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_executes(GatewayInterface $gateway, Capture $request, \ArrayObject $arrayAccess, TokenInterface $token)
    {
        $request->getModel()->willReturn($arrayAccess);
        $this->setGateway($gateway);
        $this->setApi(new Payment('test_shop_id', 'test_secret_key', null, null, null, null, null));

        $httpRequest = new GetHttpRequest();
        $gateway->execute($httpRequest);

        $httpRequest->request = [];
        $request->getToken()->willReturn($token);

        $this
            ->shouldThrow(HttpResponse::class)
            ->during('execute', [$request]);
    }

    function it_supports_only_capture_request_and_array_access(
        Capture $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);
        $this->supports($request)->shouldReturn(true);
    }
}



//ActionInterface, GatewayAwareInterface, ApiAwareInterface