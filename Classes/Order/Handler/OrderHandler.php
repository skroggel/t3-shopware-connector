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

use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Event\Order\BeforeOrderCreateEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterOrderCreateEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderCreationService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderHandler
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
     * Creates an order via Shopware API
     *
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Customer $customerDto
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Order|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function createOrder(Customer $customerDto): ?Order
    {
        $this->eventDispatcher->dispatch(new BeforeOrderCreateEvent($customerDto));

        $data = [
            'guest' => $customerDto->getGuest(),
            'email' => $customerDto->getEmail(),
        ];

        $response = $this->apiService->fetchFromApi('checkout/order', $data);

        if (
            (empty($response['id']))
            || (empty($response['lineItems']))
        ){
            return null;
        }

        $orderDto = new Order($response);

        $this->eventDispatcher->dispatch(new AfterOrderCreateEvent($orderDto));

        return $orderDto;
    }

}
