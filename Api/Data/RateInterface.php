<?php

namespace Wexo\Shipping\Api\Data;

use Magento\Rule\Model\Condition\AbstractCondition;

interface RateInterface
{
    CONST STATUS_ENABLED = 1;
    CONST STATUS_DISABLED = 0;

    const CARRIER_TYPE = 'carrier_type';
    const METHOD_TYPE = 'method_type';
    const CONDITIONS_SERIALIZED = 'conditions_serialized';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const TITLE = 'title';
    const PRICE = 'price';
    const ALLOW_FREE = 'allow_free';
    const STORE_ID = 'store_id';

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getCarrierType();

    /**
     * @param string $carrierType
     * @return RateInterface
     */
    public function setCarrierType(string $carrierType): RateInterface;

    /**
     * @return string
     */
    public function getMethodType();

    /**
     * @param string|null $methodType
     * @return RateInterface
     */
    public function setMethodType(string $methodType): RateInterface;

    /**
     * @return string|null
     */
    public function getConditionsSerialized();

    /**
     * @param string|null $conditionalsSerialized
     * @return RateInterface
     */
    public function setConditionsSerialized($conditionalsSerialized): RateInterface;

    /**
     * @return int
     */
    public function getIsActive();

    /**
     * @param int|null $isActive
     * @return RateInterface
     */
    public function setIsActive($isActive): RateInterface;

    /**
     * @return int|null
     */
    public function getSortOrder();

    /**
     * @param int|null $sortOrder
     * @return RateInterface
     */
    public function setSortOrder($sortOrder): RateInterface;

    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @param string $title
     * @return RateInterface
     */
    public function setTitle(string $title): RateInterface;

    /**
     * @return string
     */
    public function getPrice();

    /**
     * @param float|null $price
     * @return RateInterface
     */
    public function setPrice(float $price): RateInterface;

    /**
     * @return bool
     */
    public function getAllowFree();

    /**
     * @param bool $allowFree
     * @return mixed
     */
    public function setAllowFree($allowFree);

    /**
     * @return string|null
     */
    public function getStoreId();

    /**
     * @param int $storeIds
     * @return RateInterface
     */
    public function setStoreId($storeIds): RateInterface;

    /**
     * @return AbstractCondition
     */
    public function getConditions();

    /**
     * @param AbstractCondition $condition
     * @return RateInterface
     */
    public function setConditions(AbstractCondition $condition);
}
