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

use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class DirectDownloads
 *
 * Handles creation of guest orders and provides direct download links
 * for digital products via the Shopware Store API.
 *
 * Usage:
 *   $service = new DirectDownloads($shopwareApiService, $languageId);
 *   $links = $service->createOrderAndGetDownloads($productId);
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DirectDownloads
{
    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService
     */
    protected ShopwareApiService $apiService;


    /**
     * DirectDownloads constructor.
     *
     * @param ShopwareApiService $apiService  The Shopware API service dependency.
     */
    public function __construct(ShopwareApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    /**
     * Creates a guest order for the given product, marks the order as paid,
     * and returns the available direct download links.
     *
     * @param string $productId The product ID of the digital product to order.
     * @param array $settings statings settings
     * @return string[] Array of absolute download URLs.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function createOrderAndGetDownloads(string $productId, array $settings = []): array
    {
        $files = [];

        // 1. Get default sales channel context
        $context = $this->apiService->fetchFromApi(
            'context',
            [],
            'GET'
        );

        $countryId = $context['salesChannel']['countryId'] ?? null;
        $salesChannelUrl = $context['salesChannel']['domains'][0]['url'] ?? null;

        if (!$countryId || !$salesChannelUrl) {
            return $files;
        }

        // 2. Register guest customer - if the context-token does not resolve to one
        if (empty($context['customer'])) {
            $registrationResult = $this->apiService->fetchFromApi(
                'account/register',
                [
                    'guest' => true,
                    'firstName' => $settings['anonymousGuestUser']['firstName'] ?? 'Max',
                    'lastName' => $settings['anonymousGuestUser']['lastName'] ?? 'Mustermann',
                    'email' => $settings['anonymousGuestUser']['email'] ?? 'guest@example.com',
                    'billingAddress' => [
                        'street' => $settings['anonymousGuestUser']['street'] ?? 'Street 1',
                        'zipcode' => $settings['anonymousGuestUser']['zip'] ?? '12345',
                        'city' => $settings['anonymousGuestUser']['city'] ?? 'City',
                        'countryId' => $countryId
                    ],
                    'storefrontUrl' => $salesChannelUrl
                ],
            );

            if (empty($registrationResult['id'])) {
                return $files;
            }
        }

        // 3. Add product to cart
        $cartAddResult = $this->apiService->fetchFromApi(
            'checkout/cart/line-item',
            [
                'items' => [[
                    'id' => $productId,
                    'referencedId' => $productId,
                    'type' => 'product',
                    'stackable' => true,
                    'removable' => true,
                    'quantity' => 1,
                ]]
            ],
        );


     //   DebuggerUtility::var_dump($cartAddResult );

       // die();

        if (empty($cartAddResult['lineItems'])) {
            return $files;
        }

        // 4. Create order
        $orderResult = $this->apiService->fetchFromApi(
            'checkout/order',
            [
                'guest' => true,
                'email' => $settings['anonymousGuestUser']['email'] ?? 'guest@example.com',
            ],
        );

        $orderId = $orderResult['id'] ?? null;
        $lineItems = $orderResult['lineItems'] ?? [];
        $transactionId = $orderResult['transactions'][0]['id'] ?? null;

        if (!$orderId || !$lineItems || !$transactionId) {
            return $files;
        }

        // 5. Set payment state to "paid"
        $paymentResult = $this->apiService->fetchFromAdminApi(
            '_action/order_transaction/' . $transactionId . '/state/paid',
        );

        if (($paymentResult['technicalName'] ?? '') !== 'paid') {
            return $files;
        }

        // 6. Update order state (process -> complete)
        $this->apiService->fetchFromAdminApi(
            '_action/order/' . $orderId . '/state/process',
        );

        $this->apiService->fetchFromAdminApi(
            '_action/order/' . $orderId . '/state/complete',
        );

        // 7. Fetch files
        foreach ($lineItems as $lineItem) {
            foreach ($lineItem['downloads'] ?? [] as $download) {
                $downloadId = $download['id'] ?? null;
                if (! $downloadId ) {
                    continue;
                }

                // filename + extension from meta data
                $filename = $lineItem['id'] . '.bin';
                if (!empty($download['media']['fileName'])) {
                    $filename = $download['media']['fileName'];
                    if (!empty($download['media']['fileExtension'])) {
                        $filename .= '.' . $download['media']['fileExtension'];
                    }
                }

                $fileContent = $this->apiService->fetchFromApi(
                    sprintf('order/download/%s/%s', $orderId, $downloadId ),
                    [],
                    'GET',
                    0,
                    true
                );

                $files[$filename] = $fileContent;
            }
        }

        return $files;
    }

}
