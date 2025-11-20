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
 */
class Cart
{

    /**
     * @var array
     */
    protected array $cartData = [];


    /**
     * @param array $cartData
     * @return void
     */
    public function setCartData(array $cartData): void
    {
        $this->cartData = $cartData;
    }


    /**
     * @return array
     */
    public function getCartData(): array
    {
        return $this->cartData;
    }


    /**
     * Fügt ein einzelnes Item im API-Format hinzu
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

        if (!isset($this->cartData['lineItems'])) {
            $this->cartData['lineItems'] = [];
        }

        $this->cartData['lineItems'][] = [
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
        $this->cartData['lineItems'] = [];

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
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->cartData['lineItems'];
    }


    /**
     * @return bool
     */
    public function hasLineItems(): bool
    {
        return !empty($this->cartData['lineItems']);
    }


    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->cartData['transactions'];
    }


    /**
     * Gibt das DTO als Array für die Shopware-API zurück
     *
     * @return array
     */
    public function toApiRequestArray(): array
    {
        return ['items' => $this->cartData['lineItems']];
    }

}
