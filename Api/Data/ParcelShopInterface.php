<?php

namespace Wexo\Shipping\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ParcelShopInterface extends ExtensibleDataInterface
{
    /**
     * @return ?string
     */
    public function getNumber(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setNumber(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getCompanyName(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCompanyName(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getStreetName(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setStreetName(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getZipCode(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setZipCode(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getCity(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCity(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getCountryCode(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCountryCode(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getLongitude(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setLongitude(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getLatitude(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setLatitude(string $string): ParcelShopInterface;

    /**
     * @return ?string
     */
    public function getOpeningHours(): ?string;

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setOpeningHours(string $string): ParcelShopInterface;
}
