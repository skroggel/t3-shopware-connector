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

namespace Madj2k\ShopwareConnector\Order\Handler;

use Madj2k\ShopwareConnector\Domain\Enum\PaymentStatus;
use Madj2k\ShopwareConnector\Event\Order\BeforePaymentSetEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterPaymentSetEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class OrderPaymentService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PaymentHandler
{

    /**
     * @param \Madj2k\ShopwareConnector\Service\ShopwareApiService $apiService
     * @param \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        protected ShopwareApiService $apiService,
        protected EventDispatcherInterface $eventDispatcher,
    ) {}


    /**
     * Sets payment status via Shopware API
     *
     * @param string $transactionId
     * @param \Madj2k\ShopwareConnector\Domain\Enum\PaymentStatus $transition
     * @return bool
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function setPaymentState(string $transactionId, PaymentStatus $transition = PaymentStatus::PAID): bool
    {
        $event = new BeforePaymentSetEvent($transactionId, $transition);
        $this->eventDispatcher->dispatch($event);

        $transition = $event->getPaymentStatus();
        $response = $this->apiService->fetchFromAdminApi(
            sprintf('_action/order_transaction/%s/state/%s', $transactionId, $transition->value)
        );

        $this->eventDispatcher->dispatch(new AfterPaymentSetEvent($transactionId, $event->getPaymentStatus()));

        if (($response['technicalName'] ?? '') === $transition->resultState()) {
            return true;
        }

        return false;
    }

}
