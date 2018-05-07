<?php

namespace Locastic\SyliusHTPayWayPlugin\Bridge;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\PaymentInterface;

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
            $request->setResult($payment->getDetails());

            return;
        }

        $details = [];

        $details['pgwOrderId'] = $order->getNumber();
        $details['pgwAmount'] = $order->getTotal();

        $customer = $order->getCustomer();

        if (null !== $customer) {
            $details['pgwEmail'] = $customer->getEmail();
        }

//        $details['pgwLanguage'] = $order->getUser()->getLocale();

        $billingAddress = $order->getBillingAddress();

        if (null !== $billingAddress) {
            $details['pgwFirstName'] = $billingAddress->getFirstName();
            $details['pgwLastName'] = $billingAddress->getLastName();
            $details['pgwStreet'] = $billingAddress->getStreet();
            $details['pgwCity'] = $billingAddress->getCity();
            $details['pgwPostCode'] = $billingAddress->getPostcode();

            if ($billingAddress->getCountryCode()) {
                $details['pgwCountry'] = $billingAddress->getCountryCode();
            }

            $details['pgwPhoneNumber'] = $billingAddress->getPhoneNumber();
        }


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
