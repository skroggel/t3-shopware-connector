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

namespace Madj2k\ShopwareConnector\Event\Order;

use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Domain\Enum\OrderStatus;

/**
 * Class BeforeOrderStatusSetEvent
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BeforeOrderStatusSetEvent
{

    /**
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Order $orderDto
     * @param \Madj2k\ShopwareConnector\Domain\Enum\OrderStatus $targetStatus
     */
    public function __construct(
        protected Order       $orderDto,
        protected OrderStatus $targetStatus
    ) {}



    /**
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Order
     */
    public function getOrderDto(): Order
    {
        return $this->orderDto;
    }


    /**
     * @return \Madj2k\ShopwareConnector\Domain\Enum\OrderStatus
     */
    public function getTargetStatus(): OrderStatus
    {
        return $this->targetStatus;
    }


    /**
     * @param \Madj2k\ShopwareConnector\Domain\Enum\OrderStatus $targetStatus
     * @return void
     */
    public function setTargetStatus(OrderStatus $targetStatus): void
    {
        $this->targetStatus = $targetStatus;
    }
}
