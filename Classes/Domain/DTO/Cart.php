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
 * Class Cart
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @see https://shopware.stoplight.io/docs/store-api/882a9b9e86b10-fetch-or-create-a-cart
 *
 *  ! This is read-only except for the lineItems!
 */
class Cart extends AbstractDto
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }


    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->data['token'] ?? '';
    }


    /**
     * @return array
     */
    public function getPrice(): array
    {
        return $this->data['price'] ?? [];
    }


    /**
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->data['lineItems'] ?? [];
    }


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->data['errors'] ?? [];
    }


    /**
     * @return array
     */
    public function getDeliveries(): array
    {
        return $this->data['deliveries'] ?? [];
    }


    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->data['transactions'] ?? [];
    }

    /**
     * @return bool
     */
    public function getModified(): bool
    {
        return $this->data['modified'] ?? false;
    }

    /**
     * @return string
     */
    public function getCustomerComment(): string
    {
        return $this->data['customerComment'] ?? '';
    }

    /**
     * @return string
     */
    public function getAffiliateCode(): string
    {
        return $this->data['affiliateCode'] ?? '';
    }

    /**
     * @return string
     */
    public function getCampaignCode(): string
    {
        return $this->data['campaignCode'] ?? '';
    }


    /**
     * @return string
     */
    public function getApiAlias(): string
    {
        return $this->data['apiAlias'] ?? '';
    }


    /**
     * Adds a single item
     *
     * @param string $id
     * @param string $referencedId
     * @param string $type
     * @param bool $stackable
     * @param bool $removable
     * @param int $quantity
     * @return void
     */
    public function addLineItem(
        string $id,
        string $referencedId,
        string $type = 'product',
        bool $stackable = true,
        bool $removable = true,
        int $quantity = 1
    ): void {

        if (!isset($this->data['lineItems'])) {
            $this->data['lineItems'] = [];
        }

        $this->data['lineItems'][] = [
            'id' => $id,
            'referencedId' => $referencedId,
            'type' => $type,
            'stackable' => $stackable,
            'removable' => $removable,
            'quantity' => $quantity,
        ];
    }


    /**
     * Setzt mehrere Items mithilfe von addItem()
     *
     * @param array $items
     * @return void
     */
    public function setLineItems(array $items): void
    {
        $this->data['lineItems'] = [];

        foreach ($items as $item) {
            $this->addLineItem(
                $item['id'] ?? '',
                $item['referencedId'] ?? '',
                $item['type'] ?? '',
                $item['stackable'] ?? true,
                $item['removable'] ?? true,
                $item['quantity'] ?? 1
            );
        }
    }


    /**
     * @return bool
     */
    public function hasLineItems(): bool
    {
        return !empty($this->data['lineItems']);
    }


    /**
     * @return array
     */
    public function toApiRequestArray(): array
    {
        return ['items' => $this->data['lineItems']];
    }

}
