<?php

namespace Locastic\SyliusHTPayWayPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

class ConvertPaymentAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = $payment->getDetails();
        $details['pgwOrderId'] = $payment->getNumber();
        $details['pgwAmount'] = $payment->getTotalAmount();
        $details['pgwEmail'] = $payment->getClientEmail();

        $request->setResult($details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            'array' === $request->getTo() &&
            $request->getSource() instanceof PaymentInterface;
    }
}
