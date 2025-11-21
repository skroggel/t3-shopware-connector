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

use Madj2k\ShopwareConnector\Domain\DTO\Customer;

/**
 * Class AfterCustomerRegisterEvent
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AfterCustomerRegisterEvent
{

    /**
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Customer $customerDto
     */
    public function __construct(
        protected Customer $customerDto
    ) {}


    /**
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Customer
     */
    public function getCustomerDto(): Customer
    {
        return $this->customerDto;
    }
}
