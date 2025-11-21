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

namespace Madj2k\ShopwareConnector\Order;

use Madj2k\ShopwareConnector\Domain\DTO\Cart;
use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Order\Handler\CartHandler;
use Madj2k\ShopwareConnector\Order\Handler\ContextHandler;
use Madj2k\ShopwareConnector\Order\Handler\CustomerHandler;
use Madj2k\ShopwareConnector\Order\Handler\DownloadHandler;
use Madj2k\ShopwareConnector\Order\Handler\OrderHandler;
use Madj2k\ShopwareConnector\Order\Handler\PaymentHandler;
use Madj2k\ShopwareConnector\Order\Handler\StatusHandler;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class OrderManager
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderManager
{

    /**
     * @param \Madj2k\ShopwareConnector\Order\Handler\ContextHandler $contextProvider
     * @param \Madj2k\ShopwareConnector\Order\Handler\CustomerHandler $customerHandler
     * @param \Madj2k\ShopwareConnector\Order\Handler\CartHandler $cartHandler
     * @param \Madj2k\ShopwareConnector\Order\Handler\OrderHandler $creationHandler
     * @param \Madj2k\ShopwareConnector\Order\Handler\PaymentHandler $paymentHandler
     * @param \Madj2k\ShopwareConnector\Order\Handler\StatusHandler $statusHandler
     * @param \Madj2k\ShopwareConnector\Order\Handler\DownloadHandler $downloadHandler
     */
    public function __construct(
        protected ContextHandler  $contextProvider,
        protected CustomerHandler $customerHandler,
        protected CartHandler     $cartHandler,
        protected OrderHandler    $creationHandler,
        protected PaymentHandler  $paymentHandler,
        protected StatusHandler   $statusHandler,
        protected DownloadHandler $downloadHandler
    ) {}


    /**
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Cart $cartDto
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Customer|null $customerDto
     * @param array $settings
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function processFreeDownloads(
        Cart  $cartDto,
        ?Customer $customerDto = null,
        array $settings = []
    ): ?array {

        // 1. get context, may contain a user-object based on the context-token from the session
        $contextDto = $this->contextProvider->provide();

        // 2. register customer or guest
        if (
            (! $customerDto)
            || (! $customerDto->getId())
        ){
            $customerDto = new Customer();
            $customerDto->populateGuestFromSettings($settings);
        }

        $customerDto = $this->customerHandler->register($contextDto, $customerDto);
        if (!$customerDto) {
            return null;
        }

        // 3. send cart
        $cartDto = $this->cartHandler->addToCart($cartDto);
        if (!$cartDto) {
            return null;
        }

        // 4. create order for customer
        $orderDto = $this->creationHandler->createOrder($customerDto);
        if (
            (!$orderDto)
            || (!$orderDto->getTransactions())
        ){
            return null;
        }

        // 5. set payment status
        foreach ($orderDto->getTransactions() as $transaction) {
            if (! $this->paymentHandler->setPaymentState($transaction['id'])) {
                return null;
            }
        }

        // 6. set order status
        if (! $this->statusHandler->setStatus($orderDto)) {
            return null;
        }

        // 7. get downloads
        return $this->downloadHandler->getDownloads($orderDto);
    }

}
