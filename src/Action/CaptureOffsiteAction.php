<?php

namespace Locastic\SyliusHTPayWayPlugin\Action;

use Locastic\TcomPayWay\AuthorizeForm\Model\Payment as Api;
use Locastic\TcomPayWay\Helpers\CardTypeInterpreter;
use Locastic\TcomPayWay\Helpers\ResponseCodeInterpreter;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;

/**
 * @property Api $api
 */
class CaptureOffsiteAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
        $this->apiClass = Api::class;
    }

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $httpRequest = new GetHttpRequest();
        $this->gateway->execute($httpRequest);

        //we are back from ht payway site so we have to just update model and complete action
        if (isset($httpRequest->request['pgw_trace_ref'])) {
            $model['tcompayway_response'] = $this->checkAndUpdateResponse($httpRequest->request);

            return;
        }

        $this->api->setPgwAmount($model['pgwAmount']);
        $this->api->setPgwOrderId($model['pgwOrderId']);
        $this->api->setPgwEmail($model['pgwEmail']);
        $this->api->setPgwSuccessUrl($request->getToken()->getTargetUrl());
        $this->api->setPgwFailureUrl($request->getToken()->getTargetUrl());

        $this->api->setPgwFirstName($model['pgwFirstName']);
        $this->api->setPgwLastName($model['pgwLastName']);
        $this->api->setPgwStreet($model['pgwStreet']);
        $this->api->setPgwCity($model['pgwCity']);
        $this->api->setPgwPostCode($model['pgwPostCode']);
        $this->api->setPgwCountry($model['pgwCountry']);
        $this->api->setPgwPhoneNumber($model['pgwPhoneNumber']);

        $this->api->setPgwLanguage($model['pgwLanguage']);
        $this->api->setPgwMerchantData($model['pgwMerchantData']);
        $this->api->setPgwOrderInfo($model['pgwOrderInfo']);
        $this->api->setPgwOrderItems($model['pgwOrderItems']);

        $renderTemplate = new RenderTemplate(
            $this->templateName, array(
                'payment' => $this->api,
            )
        );
        $this->gateway->execute($renderTemplate);

        throw new HttpResponse($renderTemplate->getResult());
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof Capture && $request->getModel() instanceof \ArrayAccess;
    }

    protected function checkAndUpdateResponse($pgwResponse)
    {
        if (!$this->api->isPgwResponseValid($pgwResponse)) {
            throw new RequestNotSupportedException('Not valid PGW Response');
        }

        // tcompayway request failed
        if (isset($pgwResponse['pgw_result_code'])) {
            $pgwResponse['error'] = ResponseCodeInterpreter::getPgwResultCode($pgwResponse['pgw_result_code']);

            return $pgwResponse;
        }

        // tcom request success, add status code 0 manually
        $pgwResponse['credit_card'] = CardTypeInterpreter::getPgwCardType($pgwResponse['pgw_card_type_id']);
        $pgwResponse['pgw_result_code'] = 0;

        return $pgwResponse;
    }
}