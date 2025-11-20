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

namespace Madj2k\ShopwareConnector\Domain\Enum;

/**
 * Enum PaymentStatus
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
enum PaymentStatus: string
{
    case REOPEN = 'open';
    case FAIL = 'failed';
    case AUTHORIZE = 'authorize';
    case REFUND_PARTIALLY = 'refund_partially';
    case REFUND = 'refund';
    case DO_PAY = 'do_pay';
    case PAID = 'paid';
    case PAID_PARTIALLY = 'paid_partially';
    case REMIND = 'remind';
    case CANCEL = 'cancel';


    /**
     * @return string
     */
    public function resultState(): string
    {
        return match ($this) {
            self::REOPEN => 'open',
            self::FAIL => 'failed',
            self::AUTHORIZE => 'authorized',
            self::REFUND_PARTIALLY => 'refunded_partially',
            self::REFUND => 'refunded',
            self::DO_PAY => 'in_progress',
            self::PAID => 'paid',
            self::PAID_PARTIALLY => 'paid_partially',
            self::REMIND => 'reminded',
            self::CANCEL => 'cancelled',
        };
    }

}
