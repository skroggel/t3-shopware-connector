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


namespace Madj2k\ShopwareConnector\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Property
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Property extends AbstractEntity
{
    /**
     * @var string
     */
    protected string $shopwareId = '';

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $value = '';

    /**
     * @var string
     */
    protected string $groupName = '';

    /**
     * @var int
     */
    protected int $sorting = 0;


    /**
     * Get the Shopware ID
     *
     * @return string
     */
    public function getShopwareId(): string
    {
        return $this->shopwareId;
    }

    /**
     * Set the Shopware ID
     *
     * @param string $shopwareId
     * @return void
     */
    public function setShopwareId(string $shopwareId): void
    {
        $this->shopwareId = $shopwareId;
    }

    /**
     * Get the property name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the property name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the property value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the property value
     *
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the group name
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * Set the group name
     *
     * @param string $groupName
     * @return void
     */
    public function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }

    /**
     * Get the sorting value
     *
     * @return int
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Set the sorting value
     *
     * @param int $sorting
     * @return void
     */
    public function setSorting(int $sorting): void
    {
        $this->sorting = $sorting;
    }
}
