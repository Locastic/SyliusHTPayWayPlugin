<?php

namespace Locastic\SyliusHTPayWayPlugin\Validator\Constraints;

use Locastic\SyliusHTPayWayPlugin\HTPayWayFactoryInterface;
use Locastic\SyliusHTPayWayPlugin\HTPayWayOffsiteGatewayFactory;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;


final class CurrencyValidator extends ConstraintValidator
{
    public function validate($paymentMethod, Constraint $constraint): void
    {
        Assert::isInstanceOf($paymentMethod, PaymentMethodInterface::class);
        Assert::isInstanceOf($constraint, Currency::class);

        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (null === $gatewayConfig || $gatewayConfig->getFactoryName(
            ) !== HTPayWayOffsiteGatewayFactory::FACTORY_NAME) {
            return;
        }

        /** @var ChannelInterface $channel */
        foreach ($paymentMethod->getChannels() as $channel) {
            if (
                null === $channel->getBaseCurrency() ||
                false === in_array(
                    strtoupper($channel->getBaseCurrency()->getCode()),
                    HTPayWayFactoryInterface::CURRENCIES_AVAILABLE
                )
            ) {
                $message = isset($constraint->message) ? $constraint->message : null;

                $this->context->buildViolation($message)->atPath('channels')->addViolation();

                return;
            }
        }
    }
}
