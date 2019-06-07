<?php

namespace Wexo\Shipping\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\View\Asset\Repository;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use Wexo\Reepay\Api\Reepay\MethodInterface;
use Wexo\Shipping\Api\Carrier\CarrierInterface;
use Wexo\Shipping\Api\Carrier\MethodTypeHandlerInterface;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Model\RateManagement;

abstract class AbstractCarrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements CarrierInterface
{
    protected $_isFixed = true;

    /**
     * @var RateManagement
     */
    protected $rateManagement;

    /**
     * @var MethodFactory
     */
    protected $methodFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var array
     */
    protected $methodTypesHandlers;

    /**
     * @var MethodInterface|null
     */
    protected $defaultMethodTypeHandler;

    /**
     * @var Repository
     */
    protected $assetRepository;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param RateManagement $rateManagement
     * @param MethodFactory $methodFactory
     * @param ResultFactory $resultFactory
     * @param MethodTypeHandlerInterface $defaultMethodTypeHandler
     * @param Repository $assetRepository
     * @param array $methodTypeHandlers
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        RateManagement $rateManagement,
        MethodFactory $methodFactory,
        ResultFactory $resultFactory,
        Repository $assetRepository,
        MethodTypeHandlerInterface $defaultMethodTypeHandler = null,
        array $methodTypeHandlers = [],
        array $data = []
    ) {
        $this->_code = $this->getTypeName();
        $this->rateManagement = $rateManagement;
        $this->methodFactory = $methodFactory;
        $this->resultFactory = $resultFactory;
        $this->defaultMethodTypeHandler = $defaultMethodTypeHandler;
        $this->methodTypesHandlers = $methodTypeHandlers;
        $this->assetRepository = $assetRepository;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return bool|DataObject|Result|null
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->resultFactory->create();
        $rates = $this->rateManagement->getRates($this, true);
        $items = $request->getAllItems();
        if(empty($items))
            return $result;

        /** @var Quote $quote */
        $quote = reset($items)->getQuote();

        /** @var RateInterface $rate */
        foreach ($rates as $rate) {

            if($rate->getConditions() && $rate->getConditions()->validate($quote->getShippingAddress())) {

                /** @var Method $method */
                $method = $this->methodFactory->create();
                $method->setData('carrier', $this->_code);
                $method->setData('carrier_title', $this->getTitle());
                $method->setData('method', $this->makeMethodCode($rate));
                $method->setData('method_title', $rate->getTitle());
                $method->setPrice(
                    $request->getFreeShipping() && $rate->getAllowFree() ? 0 : $rate->getPrice()
                );
                $result->append($method);
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title') ?? $this->getTypeName();
    }

    /**
     * @param RateInterface $rate
     * @return string
     */
    public function makeMethodCode(RateInterface $rate)
    {
        return "{$rate->getMethodType()}_{$rate->getId()}";
    }

    /**
     * @param ShippingMethodInterface $shippingMethod
     * @param Rate $rate
     * @return void|CarrierInterface
     */
    public function convertAdditionalRateData(ShippingMethodInterface $shippingMethod, Rate $rate)
    {
        $methodType = $this->getMethodTypeByMethod($rate->getMethod());
        $shippingMethod->getExtensionAttributes()->setWexoShippingMethodType($methodType);

        $methodTypeHandler = $this->getMethodTypeHandler($methodType);
        if ($methodTypeHandler && isset($methodTypeHandler['type']) && $methodTypeHandler['type'] instanceof MethodTypeHandlerInterface) {
            $typeCode = ($methodTypeHandler['type'])->getCode();

            $shippingMethod->getExtensionAttributes()->setWexoShippingMethodTypeHandler(
                $typeCode
            );
        }
        $shippingMethod->getExtensionAttributes()->setWexoShippingMethodImage(
            $this->getImageUrl($shippingMethod, $rate, $typeCode ?? null) ?: ''
        );
    }

    /**
     * @param string $method
     * @return mixed
     */
    public function getMethodTypeByMethod(string $method)
    {
        $methodCodeParts = explode('_', $method);
        return reset($methodCodeParts);
    }

    /**
     * @param string $type
     * @return array|null
     */
    public function getMethodTypeHandler(string $type): ?array
    {
        $types = $this->getMethodTypesHandlers();
        if (isset($types[$type])) {
            return $types[$type];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getMethodTypesHandlers(): array
    {
        $handlers = [];

        foreach ($this->methodTypesHandlers as $key => $method) {
            $handlers[$key] = [
                'label' => $method['label'],
                'type' => $method['type'] ?? $this->defaultMethodTypeHandler,
                'key' => $key
            ];
        }

        return $handlers;
    }
}
