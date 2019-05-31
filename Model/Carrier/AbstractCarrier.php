<?php


namespace Wexo\Shipping\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use Wexo\Reepay\Api\Reepay\MethodInterface;
use Wexo\Shipping\Api\Carrier\CarrierInterface;
use Wexo\Shipping\Api\Carrier\MethodTypeInterface;
use Wexo\Shipping\Model\RateManagement;

abstract class AbstractCarrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements CarrierInterface
{
    protected $_isFixed = true;

    /**
     * @var RateManagement
     */
    private $rateManagement;

    /**
     * @var MethodFactory
     */
    private $methodFactory;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var array
     */
    private $methodTypes;

    /**
     * @var MethodInterface|null
     */
    private $defaultMethodType;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param RateManagement $rateManagement
     * @param MethodFactory $methodFactory
     * @param ResultFactory $resultFactory
     * @param MethodInterface|null $defaultMethodType
     * @param array $methodTypes
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        RateManagement $rateManagement,
        MethodFactory $methodFactory,
        ResultFactory $resultFactory,
        MethodInterface $defaultMethodType = null,
        array $methodTypes = [],
        array $data = []
    ) {
        $this->_code = $this->getTypeName();
        $this->rateManagement = $rateManagement;
        $this->methodFactory = $methodFactory;
        $this->resultFactory = $resultFactory;
        $this->defaultMethodType = $defaultMethodType;
        $this->methodTypes = $methodTypes;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return bool|DataObject|Result|null
     */
    public function collectRates(RateRequest $request)
    {
        if ($this->getConfigFlag('active')) {
            return false;
        }
        $result = $this->resultFactory->create();
        $rates = $this->rateManagement->getRates($this);

        foreach ($rates as $rate) {
            /** @var Method $method */
            $method = $this->methodFactory->create();
            $method->setData('carrier', $this->_code);
            $method->setData('carrier_title', $this->getTitle());
            $method->setData('method', $this->_code . $rate->getMethodType());
            $method->setData('method_title', $rate->getTitle());
            $method->setPrice(
                $request->getFreeShipping() && $rate->getAllowFree() ? 0 : $rate->getPrice()
            );
            $result->append($method);
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
     * @return MethodTypeInterface[]
     */
    public function getMethodTypes(): array
    {
        return array_map(function ($method) {
            return [
                'label' => $method['label'],
                'type' => $method['type'] ?? $this->defaultMethodType
            ];
        }, $this->methodTypes);
    }
}
