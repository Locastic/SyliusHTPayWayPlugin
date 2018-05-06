<?php

namespace spec\Locastic\SyliusHTPayWayPlugin\Action;

use Locastic\SyliusHTPayWayPlugin\Action\StatusAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Request\GetStatusInterface;
use PhpSpec\ObjectBehavior;

class StatusActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StatusAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_executes(
        GetStatusInterface $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);

        $request->markNew()->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_get_status_request_and_array_access(
        GetStatusInterface $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);
        $this->supports($request)->shouldReturn(true);
    }
}
