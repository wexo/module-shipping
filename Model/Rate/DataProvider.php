<?php

namespace Wexo\Shipping\Model\Rate;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Wexo\Shipping\Model\Rate;
use Wexo\Shipping\Model\ResourceModel\Rate\Collection;
use Wexo\Shipping\Model\ResourceModel\Rate\CollectionFactory;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var Collection
     */
    protected Collection $collection;

    /**
     * @var array
     */
    protected array $loadedData = [];

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        CollectionFactory $collectionFactory,
        protected DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    #[\Override]
    public function getData(): array
    {
        if ($this->loadedData !== []) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var $rate Rate */
        foreach ($items as $rate) {
            $this->loadedData[$rate->getId()] = $rate->getData();
        }

        $data = $this->dataPersistor->get('wexo_shipping_rate');
        if (!empty($data)) {
            $rate = $this->collection->getNewEmptyItem();
            $rate->setData($data);
            $this->loadedData[$rate->getId()] = $rate->getData();
            $this->dataPersistor->clear('wexo_shipping_rate');
        }

        return $this->loadedData;
    }
}
