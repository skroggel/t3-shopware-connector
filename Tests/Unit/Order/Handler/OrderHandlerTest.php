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

use Madj2k\ShopwareConnector\Order\Handler\OrderHandler;
use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderCreationServiceTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderHandlerTest extends TestCase
{


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function createOrderReturnsOrderDtoOnSuccess(): void
    {
        $customerDto = new Customer();
        $customerDto->setEmail('order@example.com');

        $apiResponse = [
            'id' => 'order-123',
            'lineItems' => [['id' => 'product-1']],
            'transactions' => [['id' => 'txn-789']],
        ];

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with('checkout/order')
            ->willReturn($apiResponse);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new OrderHandler($apiServiceMock, $eventDispatcherMock);
        $result = $subject->createOrder($customerDto);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertSame('order-123', $result->getId());
        $this->assertSame('txn-789', $result->getFirstTransactionId());
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function createOrderReturnsNullIfApiResponseEmpty(): void
    {
        $customerDto = new Customer();
        $customerDto->setEmail('order@example.com');

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->willReturn([]);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new OrderHandler($apiServiceMock, $eventDispatcherMock);
        $result = $subject->createOrder($customerDto);

        $this->assertNull($result);
    }

}
