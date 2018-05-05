<?php

namespace Tests\Locastic\SyliusHTPayWayPlugin\Behat\Page\External;

use Behat\Mink\Session;
use Locastic\TcomPayWay\AuthorizeForm\Helpers\SignatureGenerator;
use Payum\Core\Security\TokenInterface;
use Sylius\Behat\Page\Page;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentRepository;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\BrowserKit\Client;

final class HTPayWayPaymentPage extends Page
{
    private $securityTokenRepository;

    private $client;

    private $paymentRepository;

    public function __construct(
        Session $session,
        array $parameters,
        RepositoryInterface $securityTokenRepository,
        Client $client,
        PaymentRepository $paymentRepository
    ) {
        parent::__construct($session, $parameters);

        $this->securityTokenRepository = $securityTokenRepository;
        $this->client = $client;
        $this->paymentRepository = $paymentRepository;
    }

    public function paySuccessfully(): void
    {
        $token = $this->findToken();

        $data = $this->getSuccessResultData($token);

        $this->client->request('POST', $token->getTargetUrl(), $data);
        $this->getDriver()->visit($token->getAfterUrl());
    }

    public function payCanceled(): void
    {
        $token = $this->findToken();

        $data = $this->getFailedResultData($token, 3);

        $this->client->request('POST', $token->getTargetUrl(), $data);
        $this->getDriver()->visit($token->getAfterUrl());
    }

    private function getSuccessResultData(TokenInterface $token): array
    {
        /** @var Payment $payment */
        $payment = $this->paymentRepository->find($token->getDetails()->getId());

        $details = $payment->getDetails();

        $resultData = [
            'pgw_merchant_data' => '',
            'pgw_transaction_id' => 'test-123-123',
            'pgw_order_id' => $details['pgwOrderId'],
            'pgw_amount' => $details['pgwAmount'],
            'pgw_card_type_id' => 1,
            'pgw_signature' => '',
            'pgw_installments' => 1,
            'pgw_trace_ref' => '20000445-4d1618b5c0bc4f4b904231176e301966-20180505160557499',
        ];

        $resultData['pgw_signature'] = SignatureGenerator::generateSignatureFromArray(
            'test_key',
            $resultData
        );

        return $resultData;
    }

    private function getFailedResultData(TokenInterface $token, int $resultCode = 1): array
    {
        /** @var Payment $payment */
        $payment = $this->paymentRepository->find($token->getDetails()->getId());

        $details = $payment->getDetails();

        $resultData = [
            'pgw_merchant_data' => '',
            'pgw_order_id' => $details['pgwOrderId'],
            'pgw_result_code' => $resultCode,
            'pgw_signature' => '',
            'pgw_trace_ref' => '20000445-4d1618b5c0bc4f4b904231176e301966-20180505160557499',
        ];

        $resultData['pgw_signature'] = SignatureGenerator::generateSignatureFromArray(
            'test_key',
            $resultData
        );

        return $resultData;
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://pgwtest.ht.hr/services/payment/api/authorize-form';
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $tokens = [];

        /** @var TokenInterface $token */
        foreach ($this->securityTokenRepository->findAll() as $token) {
            if (strpos($token->getTargetUrl(), $type)) {
                $tokens[] = $token;
            }
        }

        if (count($tokens) > 0) {
            return end($tokens);
        }

        throw new \RuntimeException('Cannot find capture token, check if you are after proper checkout steps');
    }
}
