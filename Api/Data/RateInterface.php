<?php

namespace Wexo\Shipping\Api\Data;

use Magento\Rule\Model\Condition\AbstractCondition;

interface RateInterface
{
    const int STATUS_ENABLED = 1;
    const int STATUS_DISABLED = 0;

    const string CARRIER_TYPE = 'carrier_type';
    const string METHOD_TYPE = 'method_type';
    const string CONDITIONS_SERIALIZED = 'conditions_serialized';
    const string IS_ACTIVE = 'is_active';
    const string SORT_ORDER = 'sort_order';
    const string TITLE = 'title';
    const string PRICE = 'price';
    const string ALLOW_FREE = 'allow_free';
    const string STORE_ID = 'store_id';
    const string CUSTOMER_GROUPS = 'customer_groups';

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return ?string
     */
    public function getCarrierType(): ?string;

    /**
     * @param string $carrierType
     * @return RateInterface
     */
    public function setCarrierType(string $carrierType): RateInterface;

    /**
     * @return string
     */
    public function getMethodType(): string;

    /**
     * @param string $methodType
     * @return RateInterface
     */
    public function setMethodType(string $methodType): RateInterface;

    /**
     * @return ?string
     */
    public function getConditionsSerialized(): ?string;

    /**
     * @param ?string $conditionalsSerialized
     * @return RateInterface
     */
    public function setConditionsSerialized(?string $conditionalsSerialized): RateInterface;

    /**
     * @return int
     */
    public function getIsActive(): int;

    /**
     * @param int|null $isActive
     * @return RateInterface
     */
    public function setIsActive(int|null $isActive): RateInterface;

    /**
     * @return int|null
     */
    public function getSortOrder(): int|null;

    /**
     * @param int|null $sortOrder
     * @return RateInterface
     */
    public function setSortOrder(int|null $sortOrder): RateInterface;

    /**
     * @return ?string
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     * @return RateInterface
     */
    public function setTitle(string $title): RateInterface;

    /**
     * @return string
     */
    public function getPrice(): string;

    /**
     * @param float $price
     * @return RateInterface
     */
    public function setPrice(float $price): RateInterface;

    /**
     * @return bool
     */
    public function getAllowFree(): bool;

    /**
     * @param bool $allowFree
     * @return mixed
     */
    public function setAllowFree(bool $allowFree): mixed;

    /**
     * @return ?string
     */
    public function getStoreId(): ?string;

    /**
     * @param int $storeIds
     * @return RateInterface
     */
    public function setStoreId(int $storeIds): RateInterface;

    /**
     * @return AbstractCondition
     */
    public function getConditions(): AbstractCondition;

    /**
     * @param AbstractCondition $condition
     * @return RateInterface
     */
    public function setConditions(AbstractCondition $condition): RateInterface;

    /**
     * @return string
     */
    public function getCustomerGroups(): string;

    /**
     * @param string $groups
     * @return RateInterface
     */
    public function setCustomerGroups(string $groups): RateInterface;
}
