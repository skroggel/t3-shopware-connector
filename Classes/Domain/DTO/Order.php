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
 * Class Order
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Order
{

    /**
     * @var array
     */
    protected array $orderData = [];


    /**
     * @param array $orderData
     * @return void
     */
    public function setOrderData(array $orderData): void
    {
        $this->orderData = $orderData;
    }


    /**
     * @return array
     */
    public function getOrderData(): array
    {
        return $this->orderData;
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->orderData['id'] ?? '';
    }


    /**
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->orderData['lineItems'] ?? [];
    }


    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->orderData['transactions'] ?? [];
    }


    /**
     * @return string|null
     */
    public function getFirstTransactionId(): ?string
    {
        return $this->orderData['transactions'][0]['id'] ?? null;
    }


    /**
     * @return string|null
     */
    public function getOrderState(): ?string
    {
        return $this->orderData['stateMachineState']['technicalName'] ?? null;
    }

}

