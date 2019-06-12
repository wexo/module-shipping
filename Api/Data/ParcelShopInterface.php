<?php

namespace Wexo\Shipping\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ParcelShopInterface extends ExtensibleDataInterface
{
    /**
     * @return string|null
     */
    public function getNumber();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setNumber($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getCompanyName();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCompanyName($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getStreetName();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setStreetName($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getZipCode();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setZipCode($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getCity();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCity($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getCountryCode();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setCountryCode($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getLongitude();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setLongitude($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getLatitude();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setLatitude($string): ParcelShopInterface;

    /**
     * @return string|null
     */
    public function getOpeningHours();

    /**
     * @param string $string
     * @return ParcelShopInterface
     */
    public function setOpeningHours($string): ParcelShopInterface;
}