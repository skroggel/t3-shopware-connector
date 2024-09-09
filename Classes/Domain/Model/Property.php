<?php
declare(strict_types=1);
namespace Madj2k\ShopwareConnector\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or (at your option) any later version.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Property
 *
 * Represents a property of a product imported from Shopware.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Property extends AbstractEntity
{

    /**
     * @var string
     */
    protected string $name = '';


    /**
     * @var string
     */
    protected string $value = '';


    /**
     * @var string|null
     */
    protected ?string $group = null;


    /**
     * @var \Madj2k\ShopwareConnector\Domain\Model\Product|null
     */
    protected ?Product $product = null;


    /**
     * Get the property name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set the property name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * Get the property value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }


    /**
     * Set the property value.
     *
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }


    /**
     * Get the property group.
     *
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }


    /**
     * Set the property group.
     *
     * @param string|null $group
     * @return void
     */
    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }


    /**
     * Get the associated product.
     *
     * @return \Madj2k\ShopwareConnector\Domain\Model\Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }


    /**
     * Set the associated product.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Product|null $product
     * @return void
     */
    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
}
