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

use Madj2k\ShopwareConnector\Order\Handler\CartHandler;
use Madj2k\ShopwareConnector\Domain\DTO\Cart;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderCartServiceTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CartHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function addToCartReturnsUpdatedCartDto(): void
    {
        $cartDto = new Cart();
        $cartDto->addLineItem(
            'test-product-id',
            'test-product-id',
            'product',
            true,
            true,
            1
        );

        $expectedLineItems = [
            [
                'id' => 'test-product-id',
                'referencedId' => 'test-product-id',
                'type' => 'product',
                'stackable' => true,
                'removable' => true,
                'quantity' => 1,
            ]
        ];

        $responseData = [
            'token' => 'xyz123',
            'price' => [],
            'lineItems' => $expectedLineItems,
            'errors' => [],
        ];

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with('checkout/cart/line-item', ['items' => $expectedLineItems])
            ->willReturn($responseData);

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new CartHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->addToCart($cartDto);

        $this->assertInstanceOf(Cart::class, $result);
        $this->assertTrue($result->hasLineItems());
        $this->assertSame($expectedLineItems, $result->getLineItems());
        $this->assertEquals($responseData, $result->getCartData());
    }


  /**
   * @return void
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \PHPUnit\Framework\MockObject\Exception
   * @throws \Throwable
   */
    #[Test]
    public function addToCartReturnsNullIfNoItemsAdded(): void
    {
        $cartDto = new Cart();
        $cartDto->addLineItem(
            'test-product-id',
            'test-product-id',
            'product',
            true,
            true,
            1
        );

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->willReturn([]); // Keine lineItems → Abbruch

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $subject = new CartHandler($apiServiceMock, $eventDispatcherMock);

        $result = $subject->addToCart($cartDto);

        $this->assertNull($result);
    }

}
