<?php

namespace Wexo\Shipping\Model\Options;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

class CustomerGroups implements OptionSourceInterface
{
    /**
     * @var array|null
     */
    protected ?array $options = null;

    /**
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     */
    public function __construct(
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly DataObject $objectConverter
    ) {
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $customerGroups = $this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems();
            $this->options = $this->objectConverter->toOptionArray($customerGroups, 'id', 'code');
        }

        return $this->options;
    }
}
