<?php

namespace Wexo\Shipping\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Model\Carrier\AbstractCarrier;

class RateManagement
{
    /**
     * @var RateRepository
     */
    private $rateRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param RateRepository $rateRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        RateRepository $rateRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->rateRepository = $rateRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param AbstractCarrier $abstractCarrier
     * @param bool $onlyActive
     * @return RateInterface[]
     */
    public function getRates(AbstractCarrier $abstractCarrier, $onlyActive = false)
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->addFilter(RateInterface::CARRIER_TYPE, $abstractCarrier->getTypeName());

        if ($onlyActive) {
            $searchCriteriaBuilder->addFilter(RateInterface::IS_ACTIVE, 1);
        }

        return $this->rateRepository->getList(
            $searchCriteriaBuilder->create()
        )->getItems();
    }
}
