<?php

namespace Wexo\Shipping\Model;

use Exception;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Api\Data\RateInterfaceFactory;
use Wexo\Shipping\Model\ResourceModel\Rate\CollectionFactory;

class RateRepository
{
    /**
     * @var RateFactory
     */
    private $rateFactory;
    /**
     * @var CollectionProcessor
     */
    private $collectionProcessor;
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ResourceModel\Rate
     */
    private $rateResource;

    /**
     * @var RateInterface[]
     */
    private $cache = [];

    /**
     * @param RateInterfaceFactory $rateFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultFactory $searchResultFactory
     * @param CollectionProcessor $collectionProcessor
     * @param ResourceModel\Rate $rateResource
     */
    public function __construct(
        RateInterfaceFactory $rateFactory,
        CollectionFactory $collectionFactory,
        SearchResultFactory $searchResultFactory,
        CollectionProcessor $collectionProcessor,
        \Wexo\Shipping\Model\ResourceModel\Rate $rateResource
    ) {
        $this->rateFactory = $rateFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->rateResource = $rateResource;
    }

    /**
     * @param string $rateId
     * @return Rate
     * @throws NoSuchEntityException
     */
    public function get($rateId): RateInterface
    {
        if(isset($this->cache[$rateId])) {
            return $this->cache[$rateId];
        }

        $rate = $this->rateFactory->create();
        $this->rateResource->load($rate, $rateId);
        if (!$rate->getId()) {
            throw new NoSuchEntityException(__('Rate with %1 does not exist', $rateId));
        }
        $this->cache($rate);

        return $rate;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $result = $this->searchResultFactory->create([
            'data' => [
                SearchResultInterface::ITEMS => $collection->getItems(),
                SearchResultInterface::TOTAL_COUNT => $collection->getSize(),
                SearchResultInterface::SEARCH_CRITERIA => $criteria
            ]
        ]);

        foreach ($result->getItems() as $item) {
            $this->cache($item);
        }

        return $result;
    }

    /**
     * @param RateInterface $rate
     * @return RateInterface
     * @throws AlreadyExistsException
     */
    public function save(RateInterface $rate)
    {
        $this->rateResource->save($rate);
        unset($this->cache[$rate->getId()]);
        return $rate;
    }

    /**
     * @param RateInterface $rate
     * @return bool
     * @throws Exception
     */
    public function delete(RateInterface $rate)
    {
        $this->rateResource->delete($rate);
        unset($this->cache[$rate->getId()]);
        return true;
    }

    /**
     * @param RateInterface $rate
     * @return $this
     */
    public function cache(RateInterface $rate)
    {
        $this->cache[$rate->getId()] = $rate;
        return $this;
    }
}
