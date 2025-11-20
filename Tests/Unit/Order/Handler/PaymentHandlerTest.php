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

use Madj2k\ShopwareConnector\Order\Handler\PaymentHandler;
use Madj2k\ShopwareConnector\Domain\Enum\PaymentStatus;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderPaymentServiceTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PaymentHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function setPaymentStateReturnsTrueOnSuccess(): void
    {
        $transactionId = 'txn-123';

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromAdminApi')
            ->with(sprintf('_action/order_transaction/%s/state/%s', $transactionId, PaymentStatus::PAID->value))
            ->willReturn(['technicalName' => PaymentStatus::PAID->value]);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new PaymentHandler($apiServiceMock, $eventDispatcherMock);

        $this->assertTrue($subject->setPaymentState($transactionId));
    }


    /**
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test] public function setPaymentStateReturnsFalseOnFailure(): void
    {
        $transactionId = 'txn-456';

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromAdminApi')
            ->willReturn([]); // no success key

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new PaymentHandler($apiServiceMock, $eventDispatcherMock);

        $this->assertFalse($subject->setPaymentState($transactionId));
    }

}
