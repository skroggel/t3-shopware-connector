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

use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Domain\Enum\OrderStatus;
use Madj2k\ShopwareConnector\Event\Order\BeforeOrderStatusSetEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterOrderStatusSetEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class OrderStatusService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class StatusHandler
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
     * @return array[]
     */
    private function getTransitionSteps(): array
    {
        return [
            OrderStatus::REOPEN->value => [OrderStatus::REOPEN],
            OrderStatus::PROCESS->value => [OrderStatus::PROCESS],
            OrderStatus::COMPLETE->value => [
                OrderStatus::PROCESS,
                OrderStatus::COMPLETE,
            ],
            OrderStatus::CANCEL->value => [OrderStatus::CANCEL],
        ];
    }


    /**
     * Sets target status and all statues in between
     *
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Order $orderDto
     * @param \Madj2k\ShopwareConnector\Domain\Enum\OrderStatus $targetStatus
     * @return bool
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function setStatus(
        Order        $orderDto,
        OrderStatus $targetStatus = OrderStatus::COMPLETE
    ): bool {

        $event = new BeforeOrderStatusSetEvent($orderDto, $targetStatus);
        $this->eventDispatcher->dispatch($event);

        $currentStatus = $orderDto->getOrderState();
        $steps = $event->getTargetStatus()->transitionStates() ?? [];

        foreach ($steps as $transition) {
            $transitionValue = $transition->value;

            if ($transitionValue === $currentStatus) {
                continue;
            }

            $response = $this->apiService->fetchFromAdminApi(
                sprintf('_action/order/%s/state/%s', $orderDto->getId(), $transitionValue)
            );

            if (($response['technicalName'] ?? '') !==  $transition->resultState()) {
                return false;
            }

            $currentStatus = $transitionValue;
        }

        $this->eventDispatcher->dispatch(new AfterOrderStatusSetEvent($orderDto, $event->getTargetStatus()));

        return true;
    }

}
