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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Category
 *
 * Represents a category imported from Shopware.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Category extends AbstractEntity
{

    /**
     * @var string
     */
    protected string $name = '';


    /**
     * @var string
     */
    protected string $slug = '';


    /**
     * @var string
     */
    protected string $description = '';


    /**
     * @var \Madj2k\ShopwareConnector\Domain\Model\Category|null
     */
    protected ?Category $parentCategory = null;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Category>
     */
    protected ObjectStorage $subCategories;


    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->subCategories = new ObjectStorage();
    }


    /**
     * Get the category name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set the category name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * Get the category slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * Set the category slug.
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }


    /**
     * Get the category description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Set the category description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * Get the parent category.
     *
     * @return \Madj2k\ShopwareConnector\Domain\Model\Category|null
     */
    public function getParentCategory(): ?Category
    {
        return $this->parentCategory;
    }


    /**
     * Set the parent category.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category|null $parentCategory
     * @return void
     */
    public function setParentCategory(?Category $parentCategory): void
    {
        $this->parentCategory = $parentCategory;
    }


    /**
     * Get the subcategories.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Madj2k\ShopwareConnector\Domain\Model\Category>
     */
    public function getSubCategories(): ObjectStorage
    {
        return $this->subCategories;
    }


    /**
     * Adds a subcategory.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category $category
     * @return void
     */
    public function addSubCategory(Category $category): void
    {
        $this->subCategories->attach($category);
    }


    /**
     * Removes a subcategory.
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category $category
     * @return void
     */
    public function removeSubCategory(Category $category): void
    {
        $this->subCategories->detach($category);
    }
}
