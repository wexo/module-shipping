<?php

namespace Wexo\Shipping\Api\Data;

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

    public function getId();

    public function getCarrierType(): ?string;

    public function setCarrierType(string $carrierType): RateInterface;

    public function getMethodType(): ?string;

    public function setMethodType(string $methodType): RateInterface;

    public function getConditionsSerialized(): ?string;

    public function setConditionsSerialized(string $conditionalsSerialized): RateInterface;

    public function getIsActive(): ?bool;

    public function setIsActive(bool $isActive): RateInterface;

    public function getSortOrder(): ?int;

    public function setSortOrder(int $sortOrder): RateInterface;

    public function getTitle(): ?string;

    public function setTitle(string $title): RateInterface;

    public function getPrice(): ?float;

    public function setPrice(float $price): RateInterface;

    public function getAllowFree(): ?bool;

    public function setAllowFree(bool $allowFree);
}
