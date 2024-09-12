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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Product
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Product extends AbstractEntity
{

    /**
     * @var string
     */
    protected string $swId = '';


    /**
     * @var string
     */
    protected string $swLanguageId = '';


    /**
     * @var string
     */
    protected string $swManufacturerId = '';


    /**
     * @var \Madj2k\ShopwareConnector\Domain\Model\Product|null
     */
    protected ?Product $parent = null;


    /**
     * @var string
     */
    protected string $productNumber = '';


    /**
     * @var string
     */
    protected string $name = '';


    /**
     * @var string
     */
    protected string $ean = '';


    /**
     * @var bool
     */
    protected bool $isNew = false;


    /**
     * @var string
     */
    protected string $customFields = '';


    /**
     * @var int
     */
    protected int $releaseDate = 0;


    /**
     * @var float
     */
    protected float $price = 0.00;


    /**
     * @var string
     */
    protected string $calculatedPrice = '';


    /**
     * @var int
     */
    protected int $availableStock = 0;


    /**
     * @var int
     */
    protected int $stock = 0;


    /**
     * @var bool
     */
    protected bool $available = false;


    /**
     * @var int
     */
    protected int $restockTime = 0;


    /**
     * @var bool
     */
    protected bool $shippingFree = false;


    /**
     * @var string
     */
    protected string $deliveryTime = '';


    /**
     * @var int
     */
    protected int $purchaseSteps = 0;


    /**
     * @var int
     */
    protected int $maxPurchase = 0;


    /**
     * @var int
     */
    protected int $minPurchase = 0;


    /**
     * @var string
     */
    protected string $purchaseUnit = '';


    /**
     * @var string
     */
    protected string $packUnit = '';


    /**
     * @var string
     */
    protected string $packUnitPlural = '';


    /**
     * @var float
     */
    protected float $weight = 0.00;


    /**
     * @var float
     */
    protected float $width = 0.00;


    /**
     * @var float
     */
    protected float $height = 0.00;


    /**
     * @var float
     */
    protected float $length = 0.00;


    /**
     * @var string
     */
    protected string $tags = '';


    /**
     * @var string
     */
    protected string $description = '';


    /**
     * @var string
     */
    protected string $cover = '';


    /**
     * @var string
     */
    protected string $coverMimeType = '';


    /**
     * @var string
     */
    protected string $metaTitle = '';


    /**
     * @var string
     */
    protected string $metaDescription = '';


    /**
     * @var string
     */
    protected string $metaKeywords = '';


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Category>
     */
    protected ObjectStorage $categories;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Property>
     */
    protected ObjectStorage $properties;


    /**
     * Constructor to initialize ObjectStorages
     */
    public function __construct()
    {
        $this->categories = new ObjectStorage();
        $this->properties = new ObjectStorage();
    }


    /**
     * Get swId
     *
     * @return string
     */
    public function getSwId(): string
    {
        return $this->swId;
    }


    /**
     * Set swId
     *
     * @param string $swId
     * @return void
     */
    public function setSwId(string $swId): void
    {
        $this->swId = $swId;
    }


    /**
     * Get swLanguageId
     *
     * @return string
     */
    public function getSwLanguageId(): string
    {
        return $this->swLanguageId;
    }


    /**
     * Set swLanguageId
     *
     * @param string $swLanguageId
     * @return void
     */
    public function setSwLanguageId(string $swLanguageId): void
    {
        $this->swLanguageId = $swLanguageId;
    }


    /**
     * Get swManufacturerId
     *
     * @return string
     */
    public function getSwManufacturerId(): string
    {
        return $this->swManufacturerId;
    }


    /**
     * Set swManufacturerId
     *
     * @param string $swManufacturerId
     * @return void
     */
    public function setSwManufacturerId(string $swManufacturerId): void
    {
        $this->swManufacturerId = $swManufacturerId;
    }


    /**
     * Get parent
     *
     * @return \Madj2k\ShopwareConnector\Domain\Model\Product|null
     */
    public function getParent(): ?Product
    {
        return $this->parent;
    }


    /**
     * Set parent
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Product|null $parent
     * @return void
     */
    public function setParent(?Product $parent): void
    {
        $this->parent = $parent;
    }


    /**
     * Get productNumber
     *
     * @return string
     */
    public function getProductNumber(): string
    {
        return $this->productNumber;
    }


    /**
     * Set productNumber
     *
     * @param string $productNumber
     * @return void
     */
    public function setProductNumber(string $productNumber): void
    {
        $this->productNumber = $productNumber;
    }


    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * Get ean
     *
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }


    /**
     * Set ean
     *
     * @param string $ean
     * @return void
     */
    public function setEan(string $ean): void
    {
        $this->ean = $ean;
    }


    /**
     * Get isNew
     *
     * @return bool
     */
    public function getIsNew(): bool
    {
        return $this->isNew;
    }


    /**
     * Set isNew
     *
     * @param bool $isNew
     * @return void
     */
    public function setIsNew(bool $isNew): void
    {
        $this->isNew = $isNew;
    }


    /**
     * Get customFields
     *
     * @return string
     */
    public function getCustomFields(): string
    {
        return $this->customFields;
    }


    /**
     * Set customFields
     *
     * @param string $customFields
     * @return void
     */
    public function setCustomFields(string $customFields): void
    {
        $this->customFields = $customFields;
    }


    /**
     * Get releaseDate
     *
     * @return int
     */
    public function getReleaseDate(): int
    {
        return $this->releaseDate;
    }


    /**
     * Set releaseDate
     *
     * @param int $releaseDate
     * @return void
     */
    public function setReleaseDate(int $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }


    /**
     * Get price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


    /**
     * Set price
     *
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }


    /**
     * Get calculatedPrice
     *
     * @return string
     */
    public function getCalculatedPrice(): string
    {
        return $this->calculatedPrice;
    }


    /**
     * Set calculatedPrice
     *
     * @param string $calculatedPrice
     * @return void
     */
    public function setCalculatedPrice(string $calculatedPrice): void
    {
        $this->calculatedPrice = $calculatedPrice;
    }


    /**
     * Get availableStock
     *
     * @return int
     */
    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }


    /**
     * Set availableStock
     *
     * @param int $availableStock
     * @return void
     */
    public function setAvailableStock(int $availableStock): void
    {
        $this->availableStock = $availableStock;
    }


    /**
     * Get stock
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }


    /**
     * Set stock
     *
     * @param int $stock
     * @return void
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }


    /**
     * Get available
     *
     * @return bool
     */
    public function getAvailable(): bool
    {
        return $this->available;
    }


    /**
     * Set available
     *
     * @param bool $available
     * @return void
     */
    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }


    /**
     * Get restockTime
     *
     * @return int
     */
    public function getRestockTime(): int
    {
        return $this->restockTime;
    }


    /**
     * Set restockTime
     *
     * @param int $restockTime
     * @return void
     */
    public function setRestockTime(int $restockTime): void
    {
        $this->restockTime = $restockTime;
    }


    /**
     * Get shippingFree
     *
     * @return bool
     */
    public function getShippingFree(): bool
    {
        return $this->shippingFree;
    }


    /**
     * Set shippingFree
     *
     * @param bool $shippingFree
     * @return void
     */
    public function setShippingFree(bool $shippingFree): void
    {
        $this->shippingFree = $shippingFree;
    }


    /**
     * Get deliveryTime
     *
     * @return string
     */
    public function getDeliveryTime(): string
    {
        return $this->deliveryTime;
    }


    /**
     * Set deliveryTime
     *
     * @param string $deliveryTime
     * @return void
     */
    public function setDeliveryTime(string $deliveryTime): void
    {
        $this->deliveryTime = $deliveryTime;
    }


    /**
     * Get purchaseSteps
     *
     * @return int
     */
    public function getPurchaseSteps(): int
    {
        return $this->purchaseSteps;
    }


    /**
     * Set purchaseSteps
     *
     * @param int $purchaseSteps
     * @return void
     */
    public function setPurchaseSteps(int $purchaseSteps): void
    {
        $this->purchaseSteps = $purchaseSteps;
    }


    /**
     * Get maxPurchase
     *
     * @return int
     */
    public function getMaxPurchase(): int
    {
        return $this->maxPurchase;
    }


    /**
     * Set maxPurchase
     *
     * @param int $maxPurchase
     * @return void
     */
    public function setMaxPurchase(int $maxPurchase): void
    {
        $this->maxPurchase = $maxPurchase;
    }


    /**
     * Get minPurchase
     *
     * @return int
     */
    public function getMinPurchase(): int
    {
        return $this->minPurchase;
    }


    /**
     * Set minPurchase
     *
     * @param int $minPurchase
     * @return void
     */
    public function setMinPurchase(int $minPurchase): void
    {
        $this->minPurchase = $minPurchase;
    }


    /**
     * Get purchaseUnit
     *
     * @return string
     */
    public function getPurchaseUnit(): string
    {
        return $this->purchaseUnit;
    }


    /**
     * Set purchaseUnit
     *
     * @param string $purchaseUnit
     * @return void
     */
    public function setPurchaseUnit(string $purchaseUnit): void
    {
        $this->purchaseUnit = $purchaseUnit;
    }


    /**
     * Get packUnit
     *
     * @return string
     */
    public function getPackUnit(): string
    {
        return $this->packUnit;
    }


    /**
     * Set packUnit
     *
     * @param string $packUnit
     * @return void
     */
    public function setPackUnit(string $packUnit): void
    {
        $this->packUnit = $packUnit;
    }


    /**
     * Get packUnitPlural
     *
     * @return string
     */
    public function getPackUnitPlural(): string
    {
        return $this->packUnitPlural;
    }


    /**
     * Set packUnitPlural
     *
     * @param string $packUnitPlural
     * @return void
     */
    public function setPackUnitPlural(string $packUnitPlural): void
    {
        $this->packUnitPlural = $packUnitPlural;
    }


    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }


    /**
     * Set weight
     *
     * @param float $weight
     * @return void
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }


    /**
     * Get width
     *
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }


    /**
     * Set width
     *
     * @param float $width
     * @return void
     */
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }


    /**
     * Get height
     *
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }


    /**
     * Set height
     *
     * @param float $height
     * @return void
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }


    /**
     * Get length
     *
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }


    /**
     * Set length
     *
     * @param float $length
     * @return void
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }


    /**
     * Get tags
     *
     * @return string
     */
    public function getTags(): string
    {
        return $this->tags;
    }


    /**
     * Set tags
     *
     * @param string $tags
     * @return void
     */
    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * Get cover
     *
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }


    /**
     * Set cover
     *
     * @param string $cover
     * @return void
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }


    /**
     * Get coverMimeType
     *
     * @return string
     */
    public function getCoverMimeType(): string
    {
        return $this->coverMimeType;
    }


    /**
     * Set coverMimeType
     *
     * @param string $coverMimeType
     * @return void
     */
    public function setCoverMimeType(string $coverMimeType): void
    {
        $this->coverMimeType = $coverMimeType;
    }


    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
    }


    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return void
     */
    public function setMetaTitle(string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }


    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }


    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return void
     */
    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }


    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }


    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return void
     */
    public function setMetaKeywords(string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }


    /**
     * Get categories
     *
     * @return ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }


    /**
     * Adds a Category to the categories ObjectStorage.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\Madj2k\ShopwareConnector\Domain\Model\Category $category): void
    {
        $this->categories->attach($category);
    }


    /**
     * Removes a Category from the categories ObjectStorage.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category $category
     * @return void
     */
    public function removeCategory(\Madj2k\ShopwareConnector\Domain\Model\Category $category): void
    {
        $this->categories->detach($category);
    }

    /**
     * Set categories
     *
     * @param ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }


    /**
     * Get properties
     *
     * @return ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Property>
     */
    public function getProperties(): ObjectStorage
    {
        return $this->properties;
    }


    /**
     * Set properties
     *
     * @param ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Property> $properties
     * @return void
     */
    public function setProperties(ObjectStorage $properties): void
    {
        $this->properties = $properties;
    }


    /**
     * Adds a Property to the properties ObjectStorage.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Property $property
     * @return void
     */
    public function addProperty(\Madj2k\ShopwareConnector\Domain\Model\Property $property): void
    {
        $this->properties->attach($property);
    }


    /**
     * Removes a Property from the properties ObjectStorage.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Property $property
     * @return void
     */
    public function removeProperty(\Madj2k\ShopwareConnector\Domain\Model\Property $property): void
    {
        $this->properties->detach($property);
    }
}
