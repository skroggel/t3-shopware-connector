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
 * Class Category
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Category extends AbstractEntity
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
     * @var Category|null
     */
    protected ?Category $parent = null;


    /**
     * @var string
     */
    protected string $name = '';


    /**
     * @var string
     */
    protected string $description = '';


    /**
     * @var string
     */
    protected string $slug = '';


    /**
     * @var bool
     */
    protected bool $hideInMenu = false;



    /**
     * Get the Shopware ID of the category
     *
     * @return string
     */
    public function getSwId(): string
    {
        return $this->swId;
    }


    /**
     * Set the Shopware ID of the category
     *
     * @param string $swId
     * @return void
     */
    public function setSwId(string $swId): void
    {
        $this->swId = $swId;
    }


    /**
     * Get the Shopware language ID
     *
     * @return string
     */
    public function getSwLanguageId(): string
    {
        return $this->swLanguageId;
    }


    /**
     * Set the Shopware language ID
     *
     * @param string $swLanguageId
     * @return void
     */
    public function setSwLanguageId(string $swLanguageId): void
    {
        $this->swLanguageId = $swLanguageId;
    }


    /**
     * Get the parent category
     *
     * @return \Madj2k\ShopwareConnector\Domain\Model\Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }


    /**
     * Set the parent category
     *
     * @param \Madj2k\ShopwareConnector\Domain\Model\Category|null $parent
     * @return void
     */
    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }


    /**
     * Get the name of the category
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set the name of the category
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * Get the description of the category
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Set the description of the category
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * Get the slug (URL identifier) of the category
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    /**
     * Set the slug (URL identifier) of the category
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }


    /**
     * Get whether the category is hidden in the menu
     *
     * @return bool
     */
    public function getHideInMenu(): bool
    {
        return $this->hideInMenu;
    }


    /**
     * Set whether the category is hidden in the menu
     *
     * @param bool $hideInMenu
     * @return void
     */
    public function setHideInMenu(bool $hideInMenu): void
    {
        $this->hideInMenu = $hideInMenu;
    }

}
