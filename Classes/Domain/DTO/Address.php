<?php
declare(strict_types=1);

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or (at your option) any later version.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Madj2k\ShopwareConnector\Domain\DTO;

/**
 * Class AddressDto
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @see http://localhost:8000/store-api/account/register
 */
class Address extends AbstractDto
{

    /**
     * Returns id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }


    /**
     * Sets id
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->data['id'] = $id;
    }


    /**
     * Returns customerId
     *
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->data['customerId'] ?? '';
    }


    /**
     * Sets customerId
     *
     * @param string $customerId
     * @return void
     */
    public function setCustomerId(string $customerId): void
    {
        $this->data['customerId'] = $customerId;
    }


    /**
     * Returns countryId
     *
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->data['countryId'] ?? '';
    }


    /**
     * Sets countryId
     *
     * @param string $countryId
     * @return void
     */
    public function setCountryId(string $countryId): void
    {
        $this->data['countryId'] = $countryId;
    }


    /**
     * Returns countryStateId
     *
     * @return string
     */
    public function getCountryStateId(): string
    {
        return $this->data['countryStateId'] ?? '';
    }


    /**
     * Sets countryStateId
     *
     * @param string $countryStateId
     * @return void
     */
    public function setCountryStateId(string $countryStateId): void
    {
        $this->data['countryStateId'] = $countryStateId;
    }


    /**
     * Returns salutationId
     *
     * @return string
     */
    public function getSalutationId(): string
    {
        return $this->data['salutationId'] ?? '';
    }


    /**
     * Sets salutationId
     *
     * @param string $salutationId
     * @return void
     */
    public function setSalutationId(string $salutationId): void
    {
        $this->data['salutationId'] = $salutationId;
    }


    /**
     * Returns firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->data['firstName'] ?? '';
    }


    /**
     * Sets firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->data['firstName'] = $firstName;
    }


    /**
     * Returns lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->data['lastName'] ?? '';
    }


    /**
     * Sets lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->data['lastName'] = $lastName;
    }


    /**
     * Returns zipcode
     *
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->data['zipcode'] ?? '';
    }


    /**
     * Sets zipcode
     *
     * @param string $zipcode
     * @return void
     */
    public function setZipcode(string $zipcode): void
    {
        $this->data['zipcode'] = $zipcode;
    }


    /**
     * Returns city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->data['city'] ?? '';
    }


    /**
     * Sets city
     *
     * @param string $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->data['city'] = $city;
    }


    /**
     * Returns company
     *
     * @return string
     */
    public function getCompany(): string
    {
        return $this->data['company'] ?? '';
    }


    /**
     * Sets company
     *
     * @param string $company
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->data['company'] = $company;
    }


    /**
     * Returns street
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->data['street'] ?? '';
    }


    /**
     * Sets street
     *
     * @param string $street
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->data['street'] = $street;
    }


    /**
     * Returns department
     *
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->data['department'] ?? '';
    }


    /**
     * Sets department
     *
     * @param string $department
     * @return void
     */
    public function setDepartment(string $department): void
    {
        $this->data['department'] = $department;
    }


    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->data['title'] ?? '';
    }


    /**
     * Sets title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->data['title'] = $title;
    }


    /**
     * Returns phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->data['phoneNumber'] ?? '';
    }


    /**
     * Sets phoneNumber
     *
     * @param string $phoneNumber
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->data['phoneNumber'] = $phoneNumber;
    }


    /**
     * Returns additionalAddressLine1
     *
     * @return string
     */
    public function getAdditionalAddressLine1(): string
    {
        return $this->data['additionalAddressLine1'] ?? '';
    }


    /**
     * Sets additionalAddressLine1
     *
     * @param string $additionalAddressLine1
     * @return void
     */
    public function setAdditionalAddressLine1(string $additionalAddressLine1): void
    {
        $this->data['additionalAddressLine1'] = $additionalAddressLine1;
    }


    /**
     * Returns additionalAddressLine2
     *
     * @return string
     */
    public function getAdditionalAddressLine2(): string
    {
        return $this->data['additionalAddressLine2'] ?? '';
    }


    /**
     * Sets additionalAddressLine2
     *
     * @param string $additionalAddressLine2
     * @return void
     */
    public function setAdditionalAddressLine2(string $additionalAddressLine2): void
    {
        $this->data['additionalAddressLine2'] = $additionalAddressLine2;
    }


}
