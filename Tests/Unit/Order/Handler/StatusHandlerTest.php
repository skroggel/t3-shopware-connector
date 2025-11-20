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

namespace Madj2k\ShopwareConnector\Tests\Unit\Order\Handler;

use Madj2k\ShopwareConnector\Order\Handler\StatusHandler;
use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Domain\Enum\OrderStatus;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderStatusServiceTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class StatusHandlerTest extends TestCase
{


    /**
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function testSetStatusReturnsTrueOnSuccessfulTransitions(): void
    {
        $orderDto = new Order();
        $orderDto->setOrderData([
            'id' => 'order-123',
            'stateMachineState' => ['technicalName' => OrderStatus::REOPEN->value],
        ]);

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromAdminApi')
            ->willReturnOnConsecutiveCalls(
                ['technicalName' => OrderStatus::PROCESS->resultState()],   // after process
                ['technicalName' => OrderStatus::COMPLETE->resultState()]   // after complete
            );

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new StatusHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->setStatus($orderDto, OrderStatus::COMPLETE);

        $this->assertTrue($result);
    }


    /**
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function testSetStatusReturnsFalseOnFailedTransition(): void
    {
        $orderDto = new Order();
        $orderDto->setOrderData([
            'id' => 'order-123',
            'stateMachineState' => ['technicalName' => 'open'],
        ]);

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromAdminApi')
            ->willReturn([]); // Keine success-Antwort

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new StatusHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->setStatus($orderDto, OrderStatus::COMPLETE);

        $this->assertFalse($result);
    }

}
