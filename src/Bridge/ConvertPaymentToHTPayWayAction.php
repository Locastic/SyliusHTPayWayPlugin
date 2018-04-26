<?php

namespace Locastic\SyliusHTPayWayPlugin\Bridge;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Payment\Model\PaymentInterface;

class ConvertPaymentToHTPayWayAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var Order $order */
        $order = $payment->getOrder();

        if ($payment->getDetails()) {
            return;
        }

        $details = [];

        $details['pgwOrderId'] = $order->getNumber();
        $details['pgwAmount'] = $order->getTotal();
//        $details['pgwLanguage'] = $order->getUser()->getLocale();
        $details['pgwFirstName'] = $order->getBillingAddress()->getFirstName();
        $details['pgwLastName'] = $order->getBillingAddress()->getLastName();
        $details['pgwStreet'] = $order->getBillingAddress()->getStreet();
        $details['pgwCity'] = $order->getBillingAddress()->getCity();
        $details['pgwPostCode'] = $order->getBillingAddress()->getPostCode();
        if ($order->getBillingAddress()->getCountryCode()) {
            $details['pgwCountry'] = $order->getBillingAddress()->getCountryCode();
        }
        $details['pgwPhoneNumber'] = $order->getBillingAddress()->getPhoneNumber();

        $details['pgwEmail'] = $order->getCustomer()->getEmail();

        $request->setResult($details);
    }


    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array';
    }
}
