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
 * Class Product
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Product extends AbstractEntity
{

    /**
     * @var string
     */
    protected string $name = '';


    /**
     * @var string
     */
    protected string $description = '';


    /**
     * @var float
     */
    protected float $price = 0.0;


    /**
     * @var string
     */
    protected string $shopwareId = '';


    /**
     * @var string
     */
    protected string $sku = '';


    /**
     * @var int
     */
    protected int $stock = 0;


    /**
     * @var float
     */
    protected float $weight = 0.0;


    /**
     * @var float
     */
    protected float $length = 0.0;


    /**
     * @var float
     */
    protected float $width = 0.0;


    /**
     * @var float
     */
    protected float $height = 0.0;


    /**
     * @var string
     */
    protected string $manufacturer = '';


    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $releaseDate = null;


    /**
     * @var int
     */
    protected int $minimumPurchase = 0;


    /**
     * @var int
     */
    protected int $maximumPurchase = 0;


    /**
     * @var int
     */
    protected int $purchaseSteps = 0;


    /**
     * @var bool
     */
    protected bool $shippingFree = false;


    /**
     * @var string
     */
    protected string $shippingTime = '';


    /**
     * @var string
     */
    protected string $seoTitle = '';


    /**
     * @var string
     */
    protected string $seoDescription = '';


    /**
     * @var string
     */
    protected string $slug = '';


    /**
     * @var array
     */
    protected array $categories = [];


    /**
     * @var array
     */
    protected array $tags = [];


    /**
     * @var array
     */
    protected array $customFields = [];


    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set the product name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * Get the product description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Set the product description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * Get the product price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


    /**
     * Set the product price.
     *
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }


    /**
     * Get the Shopware ID of the product.
     *
     * @return string
     */
    public function getShopwareId(): string
    {
        return $this->shopwareId;
    }


    /**
     * Set the Shopware ID of the product.
     *
     * @param string $shopwareId
     * @return void
     */
    public function setShopwareId(string $shopwareId): void
    {
        $this->shopwareId = $shopwareId;
    }


    /**
     * Get the SKU of the product.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }


    /**
     * Set the SKU of the product.
     *
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }


    /**
     * Get the stock quantity of the product.
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }


    /**
     * Set the stock quantity of the product.
     *
     * @param int $stock
     * @return void
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }


    /**
     * Get the product weight.
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }


    /**
     * Set the product weight.
     *
     * @param float $weight
     * @return void
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }


    /**
     * Get the product length.
     *
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }


    /**
     * Set the product length.
     *
     * @param float $length
     * @return void
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }


    /**
     * Get the product width.
     *
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }


    /**
     * Set the product width.
     *
     * @param float $width
     * @return void
     */
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }


    /**
     * Get the product height.
     *
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }


    /**
     * Set the product height.
     *
     * @param float $height
     * @return void
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }


    /**
     * Get the manufacturer of the product.
     *
     * @return string
     */
    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }


    /**
     * Set the manufacturer of the product.
     *
     * @param string $manufacturer
     * @return void
     */
    public function setManufacturer(string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }


    /**
     * Get the release date of the product.
     *
     * @return \DateTime|null
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }


    /**
     * Set the release date of the product.
     *
     * @param \DateTime|null $releaseDate
     * @return void
     */
    public function setReleaseDate(?\DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }


    /**
     * Get the minimum purchase quantity.
     *
     * @return int
     */
    public function getMinimumPurchase(): int
    {
        return $this->minimumPurchase;
    }


    /**
     * Set the minimum purchase quantity.
     *
     * @param int $minimumPurchase
     * @return void
     */
    public function setMinimumPurchase(int $minimumPurchase): void
    {
        $this->minimumPurchase = $minimumPurchase;
    }


    /**
     * Get the maximum purchase quantity.
     *
     * @return int
     */
    public function getMaximumPurchase(): int
    {
        return $this->maximumPurchase;
    }


    /**
     * Set the maximum purchase quantity.
     *
     * @param int $maximumPurchase
     * @return void
     */
    public function setMaximumPurchase(int $maximumPurchase): void
    {
        $this->maximumPurchase = $maximumPurchase;
    }


    /**
     * Get the purchase steps.
     *
     * @return int
     */
    public function getPurchaseSteps(): int
    {
        return $this->purchaseSteps;
    }


    /**
     * Set the purchase steps.
     *
     * @param int $purchaseSteps
     * @return void
     */
    public function setPurchaseSteps(int $purchaseSteps): void
    {
        $this->purchaseSteps = $purchaseSteps;
    }


    /**
     * Get the shipping free status.
     *
     * @return bool
     */
    public function isShippingFree(): bool
    {
        return $this->shippingFree;
    }


    /**
     * Set the shipping free status.
     *
     * @param bool $shippingFree
     * @return void
     */
    public function setShippingFree(bool $shippingFree): void
    {
        $this->shippingFree = $shippingFree;
    }


    /**
     * Get the shipping time.
     *
     * @return string
     */
    public function getShippingTime(): string
    {
        return $this->shippingTime;
    }


    /**
     * Set the shipping time.
     *
     * @param string $shippingTime
     * @return void
     */
    public function setShippingTime(string $shippingTime): void
    {
        $this->shippingTime = $shippingTime;
    }


    /**
     * Get the SEO title of the product.
     *
     * @return string
     */
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }


    /**
     * Set the SEO title of the product.
     *
     * @param string $seoTitle
     * @return void
     */
    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }


    /**
     * Get the SEO description of the product.
     *
     * @return string
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }


    /**
     * Set the SEO description of the product.
     *
     * @param string $seoDescription
     * @return void
     */
    public function setSeoDescription(string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }


    /**
     * Get the slug of the product.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * Set the slug of the product.
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }


    /**
     * Get the categories of the product.
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }


    /**
     * Set the categories of the product.
     *
     * @param array $categories
     * @return void
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }


    /**
     * Get the tags of the product.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }


    /**
     * Set the tags of the product.
     *
     * @param array $tags
     * @return void
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * Get the custom fields of the product.
     *
     * @return array
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }


    /**
     * Set the custom fields of the product.
     *
     * @param array $customFields
     * @return void
     */
    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }
}
