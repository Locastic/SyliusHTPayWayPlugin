<?php

namespace Tests\Locastic\SyliusHTPayWayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Locastic\SyliusHTPayWayPlugin\HTPayWayOffsiteGatewayFactory;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class HTPayWayOffsiteContext implements Context
{
    private $sharedStorage;

    private $paymentMethodRepository;

    private $paymentMethodExampleFactory;

    private $paymentMethodTranslationFactory;

    private $paymentMethodManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        FactoryInterface $paymentMethodTranslationFactory,
        ObjectManager $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodTranslationFactory = $paymentMethodTranslationFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a payment method :paymentMethodName with a code :paymentMethodCode and HT PayWay Offsite payment gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndHtPaywayOffsitePaymentGateway(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void {
        $paymentMethod = $this->createPaymentMethodHTPayWayOffsite(
            $paymentMethodName,
            $paymentMethodCode,
            HTPayWayOffsiteGatewayFactory::FACTORY_NAME,
            'HT PayWay Offiste'
        );

        $paymentMethod->getGatewayConfig()->setConfig(
            [
                'shop_id' => 'test_shop_id',
                'secret_key' => 'test_key',
            ]
        );

        $this->paymentMethodManager->flush();
    }

    private function createPaymentMethodHTPayWayOffsite(
        string $name,
        string $code,
        string $factoryName,
        string $description = '',
        bool $addForCurrentChannel = true,
        int $position = null
    ): PaymentMethodInterface {

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create(
            [
                'name' => ucfirst($name),
                'code' => $code,
                'description' => $description,
                'gatewayName' => $factoryName,
                'gatewayFactory' => $factoryName,
                'enabled' => true,
                'channels' => ($addForCurrentChannel && $this->sharedStorage->has(
                        'channel'
                    )) ? [$this->sharedStorage->get('channel')] : [],
            ]
        );
        if (null !== $position) {
            $paymentMethod->setPosition($position);
        }

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
