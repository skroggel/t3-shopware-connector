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
 * Class Context
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Context
{

    /**
     * @var array
     */
    protected array $contextData = [];


    /**
     * @return array
     */
    public function getContextData(): array
    {
        return $this->contextData;
    }


    /**
     * @param array $contextData
     * @return void
     */
    public function setContextData(array $contextData): void
    {
        $this->contextData = $contextData;
    }


    /**
     * @return array|null
     */
    public function getSalesChannel(): ?array
    {
        return $this->contextData['salesChannel'] ?? null;
    }


    /**
     * @return string|null
     */
    public function getSalesChannelCountryId(): ?string
    {
        return $this->getSalesChannel()['countryId'] ?? null;
    }


    /**
     * @return string|null
     */
    public function getSalesChannelDomainUrl(): ?string
    {
        return $this->getSalesChannel()['domains'][0]['url'] ?? null;
    }


    /**
     * @return array|null
     */
    public function getCustomer(): ?array
    {
        return $this->contextData['customer'] ?? null;
    }

}
