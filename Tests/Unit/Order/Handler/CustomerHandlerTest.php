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

use Madj2k\ShopwareConnector\Order\Handler\CustomerHandler;
use Madj2k\ShopwareConnector\Domain\DTO\Context;
use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderCustomerServiceTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CustomerHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function registerRegistersGuestIfNoneExists(): void
    {
        $contextData = [
            'salesChannel' => [
                'countryId' => 'abc123',
                'domains' => [['url' => 'https://shop.example.com']],
            ]
        ];

        $contextDto = new Context();
        $contextDto->setContextData($contextData);

        $customerDto = new Customer();
        $customerDto->setFirstName('Test');
        $customerDto->setLastName('User');
        $customerDto->setEmail('test@example.com');
        $customerDto->setStreet('Teststraße 5');
        $customerDto->setZip('54321');
        $customerDto->setCity('Teststadt');

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with('account/register')
            ->willReturn([
                'id' => 'customer-001',
                'email' => 'test@example.com',
                'firstName' => 'Test',
                'lastName' => 'User',
            ]);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new CustomerHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->register($contextDto, $customerDto, true);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertSame('customer-001', $result->getId());
        $this->assertSame('test@example.com', $result->getEmail());
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function registerRegistersRegularUser(): void
    {
        $contextDto = new Context();
        $contextDto->setContextData([
            'salesChannel' => [
                'countryId' => 'abc123',
                'domains' => [['url' => 'https://shop.example.com']],
            ]
        ]);

        $customerDto = new Customer();
        $customerDto->setFirstName('Registered');
        $customerDto->setLastName('User');
        $customerDto->setEmail('registered@example.com');
        $customerDto->setStreet('Registerstraße 10');
        $customerDto->setZip('10101');
        $customerDto->setCity('Registerstadt');

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with('account/register')
            ->willReturn([
                'id' => 'registered-id-001',
                'email' => 'registered@example.com',
                'firstName' => 'Registered',
                'lastName' => 'User',
            ]);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new CustomerHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->register($contextDto, $customerDto, false);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertSame('registered-id-001', $result->getId());
        $this->assertSame('registered@example.com', $result->getEmail());
    }


    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function registerReturnsExistingCustomerFromContext(): void
    {
        $contextData = [
            'customer' => [
                'id' => 'existing-id',
                'email' => 'existing@example.com',
                'firstName' => 'Existing',
                'lastName' => 'User',
            ]
        ];

        $contextDto = new Context();
        $contextDto->setContextData($contextData);

        $customerDto = new Customer();

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new CustomerHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->register($contextDto, $customerDto);


        $this->assertSame('existing-id', $result->getId());
        $this->assertSame('existing@example.com', $result->getEmail());
        $this->assertSame('Existing', $result->getFirstName());
        $this->assertSame('User', $result->getLastName());
    }

}
