<?php

namespace Locastic\SyliusHTPayWayPlugin\Action;

use Locastic\TcomPayWay\Model\Payment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\ApiAwareInterface;

/**
 * @property Payment $api
 */
class StatusAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Payment::class;
    }

    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();

        if (false == isset($model['tcompayway_response'])) {
            $request->markNew();

            return;
        }

        $statusCode = $model['tcompayway_response']['pgw_result_code'];

        if (0 == $statusCode && 0 == $this->api->getPgwAuthorizationType()) {
            // because Sylius doesnt recognize authorized payment state we mark it as captured
            //$request->markAuthorized();
            $request->markCaptured();

            return;
        }

        if (0 == $statusCode && 1 == $this->api->getPgwAuthorizationType()) {
            $request->markCaptured();

            return;
        }

        if ($statusCode > 0) {
            $request->markFailed();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
