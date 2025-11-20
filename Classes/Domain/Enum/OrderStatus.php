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
 * Enum OrderStatus
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
enum OrderStatus: string
{
    case REOPEN = 'reopen';
    case PROCESS = 'process';
    case CANCEL = 'cancel';
    case COMPLETE = 'complete';


    /**
     * @return string
     */
    public function resultState(): string
    {
        return match ($this) {
            self::REOPEN => 'open',
            self::PROCESS => 'in_progress',
            self::CANCEL => 'cancelled',
            self::COMPLETE => 'completed',
        };
    }


    /**
     * @return \Madj2k\ShopwareConnector\Domain\Enum\OrderStatus[]
     */
    public function transitionStates(): array
    {

        return match ($this) {

             self::REOPEN => [self::REOPEN],
             self::PROCESS=> [self::PROCESS],
             self::COMPLETE => [self::PROCESS, self::COMPLETE],
             self::CANCEL => [self::CANCEL]
        };
    }
}
