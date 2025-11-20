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

use Madj2k\ShopwareConnector\Domain\DTO\Cart;
use Madj2k\ShopwareConnector\Event\Order\BeforeCartAddEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterCartAddEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderCartHandler
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CartHandler
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
     * Send cart content to Shopware
     *
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Cart $cartDto
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Cart|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function addToCart(Cart $cartDto): ?Cart
    {
        $this->eventDispatcher->dispatch(new BeforeCartAddEvent($cartDto));

        $response = $this->apiService->fetchFromApi('checkout/cart/line-item', $cartDto->toApiRequestArray());

        if (empty($response['lineItems'])) {
            return null;
        }

        $cartDto->setCartData($response);

        $this->eventDispatcher->dispatch(new AfterCartAddEvent($cartDto));

        return $cartDto;
    }

}
