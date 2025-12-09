<?php

namespace Wexo\Shipping\Model;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Model\Carrier\AbstractCarrier;

class RateManagement
{
    /**
     * @param RateRepository $rateRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly RateRepository $rateRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @param AbstractCarrier $abstractCarrier
     * @param bool $onlyActive
     * @return ExtensibleDataInterface[]
     */
    public function getRates(AbstractCarrier $abstractCarrier, bool $onlyActive = false): array
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->addFilter(RateInterface::CARRIER_TYPE, $abstractCarrier->getTypeName());

        if ($onlyActive) {
            $searchCriteriaBuilder->addFilter(RateInterface::IS_ACTIVE, RateInterface::STATUS_ENABLED);
        }

        return $this->rateRepository->getList(
            $searchCriteriaBuilder->create()
        )->getItems();
    }

    /**
     * @param int $rateId
     * @return RateInterface
     * @throws NoSuchEntityException
     */
    public function getRate(int $rateId): RateInterface
    {
        return $this->rateRepository->get($rateId);
    }
}
