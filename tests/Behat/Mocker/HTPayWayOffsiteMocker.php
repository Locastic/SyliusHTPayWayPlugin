<?php

namespace Tests\Locastic\SyliusHTPayWayPlugin\Behat\Mocker;

use Locastic\TcomPayWay\AuthorizeForm\Model\Payment;

use Sylius\Behat\Service\Mocker\MockerInterface;

final class HTPayWayOffsiteMocker
{

    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockApiCreatePayment(callable $action): void
    {
        $payment = \Mockery::mock('payment');

        $payment->id = 1;

        $payment
            ->shouldReceive('getPaymentUrl')
            ->andReturn('');

        $payments = \Mockery::mock('payments');

        $payments
            ->shouldReceive('create')
            ->andReturn($payment);

//        $mock = $this->mocker->mockCollaborator(Payment::class);

//        $mock
//            ->shouldReceive('setApiKey');
//
//        $mock
//            ->shouldReceive('isRecurringSubscription')
//            ->andReturn(false);
//
//        $mock->payments = $payments;

        $action();

        $this->mocker->unmockAll();
    }

}