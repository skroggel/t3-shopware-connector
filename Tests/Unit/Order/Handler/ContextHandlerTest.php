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

use Madj2k\ShopwareConnector\Order\Handler\ContextHandler;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Madj2k\ShopwareConnector\Domain\DTO\Context;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderContextProviderTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContextHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function provideReturnsContextDtoWithExpectedData(): void
    {
        $sampleContext = [
            'salesChannel' => [
                'countryId' => 'abc123',
                'domains' => [
                    ['url' => 'https://shop.example.com']
                ]
            ]
        ];

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with('context')
            ->willReturn($sampleContext);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new ContextHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->provide();

        $this->assertInstanceOf(Context::class, $result);
        $this->assertSame('abc123', $result->getSalesChannelCountryId());
        $this->assertSame('https://shop.example.com', $result->getSalesChannelDomainUrl());
    }

}
