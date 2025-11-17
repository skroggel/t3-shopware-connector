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

namespace Madj2k\ShopwareConnector\Tests\Functional\Order;

use Madj2k\ShopwareConnector\Order\DirectDownloads;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class DirectDownloadsTest
 *
 * Functional tests for DirectDownloads service
 *
 * @author Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DirectDownloadsTest extends FunctionalTestCase
{

    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService|\PHPUnit\Framework\MockObject\MockObject
     */
    private ShopwareApiService|MockObject $apiServiceMock;


    /**
     * @var \Madj2k\ShopwareConnector\Order\DirectDownloads
     */
    private DirectDownloads $subject;


    /**
     * Sets up the test environment
     *
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiServiceMock = $this->createMock(ShopwareApiService::class);
        $this->subject = new DirectDownloads($this->apiServiceMock, 'language-id-123');
    }


    /**
     * Scenario: Create order with one downloadable line item
     * Given all API calls succeed and one download with media info
     * When createOrderAndGetDownloads is called
     * Then exactly one file is returned with correct name and content
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsSingleFile(): void
    {
        $orderId       = 'order-123';
        $transactionId = 'trans-789';
        $lineItemId    = 'li-1';
        $downloadId    = 'dn-1';

        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            function (
                string $endpoint,
                array $params = [],
                string $method = 'POST',
                int $cache = 0,
                bool $raw = false
            )
            use ($orderId, $transactionId, $lineItemId, $downloadId) {
                return match ($endpoint) {
                    'context' => [
                        'salesChannel' => [
                            'countryId' => 'country-1',
                            'domains'   => [['url' => 'https://shop.example.com']],
                        ],
                    ],
                    'account/register' => ['id' => 'cust-1'],
                    'checkout/cart/line-item' => ['lineItems' => [['id' => 'prod-123']]],
                    'checkout/order' => [
                        'id' => $orderId,
                        'transactions' => [['id' => $transactionId]],
                        'lineItems' => [[
                            'id' => $lineItemId,
                            'downloads' => [[
                                'id' => $downloadId,
                                'media' => [
                                    'fileName' => 'Handbuch',
                                    'fileExtension' => 'pdf'
                                ]
                            ]],
                        ]],
                    ],
                    // Download endpoint
                    "order/download/$orderId/$downloadId" => $raw ? '%PDF-binary%' : [],
                    default => [],
                };
            }
        );

        $this->apiServiceMock->method('fetchFromAdminApi')->willReturn(['technicalName' => 'paid']);

        $result = $this->subject->createOrderAndGetDownloads('prod-123');

        self::assertCount(1, $result);
        self::assertArrayHasKey('Handbuch.pdf', $result);
        self::assertSame('%PDF-binary%', $result['Handbuch.pdf']);
    }


    /**
     * Scenario: Context response is missing countryId or salesChannel URL
     * Given no countryId or URL is returned in context
     * When createOrderAndGetDownloads is called
     * Then an empty array is returned
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsEmptyArrayIfContextIncomplete(): void
    {
        $this->apiServiceMock->method('fetchFromApi')->willReturn([
            'salesChannel' => [
                'countryId' => null,
                'domains' => [[]],
            ],
        ]);

        $result = $this->subject->createOrderAndGetDownloads('prod-123');
        self::assertSame([], $result);
    }


    /**
     * Scenario: Context token is present and valid
     * Given a context token is already set and context contains customer
     * When createOrderAndGetDownloads is called
     * Then registration is skipped and order proceeds
     */
    #[Test]
    public function createOrderAndGetDownloadsSkipsRegistrationIfContextTokenExists(): void
    {
        $orderId       = 'order-123';
        $transactionId = 'trans-123';
        $downloadId    = 'dl-123';

        // Kontext mit Kunde wird simuliert
        $this->apiServiceMock->method('getContextToken')->willReturn('context-token-abc');

        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            fn(string $endpoint) => match ($endpoint) {
                'context' => [
                    'salesChannel' => [
                        'countryId' => 'country-1',
                        'domains' => [['url' => 'https://shop.example.com']],
                    ],
                    'customer' => ['id' => 'cust-1'],
                ],
                'checkout/cart/line-item' => ['lineItems' => [['id' => 'prod-123']]],
                'checkout/order' => [
                    'id' => $orderId,
                    'transactions' => [['id' => $transactionId]],
                    'lineItems' => [[
                        'id' => 'li-1',
                        'downloads' => [[
                            'id' => $downloadId,
                            'media' => ['fileName' => 'Testfile', 'fileExtension' => 'zip']
                        ]],
                    ]],
                ],
                "order/download/$orderId/$downloadId" => '%ZIP-FILE%',
                default => []
            }
        );

        // simulate successful payment
        $this->apiServiceMock->method('fetchFromAdminApi')->willReturn(['technicalName' => 'paid']);

        $result = $this->subject->createOrderAndGetDownloads('prod-123');

        self::assertArrayHasKey('Testfile.zip', $result);
        self::assertSame('%ZIP-FILE%', $result['Testfile.zip']);
    }


    /**
     * Scenario: Registration of guest user fails
     * Given account/register returns no id
     * When createOrderAndGetDownloads is called
     * Then an empty array is returned
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsEmptyArrayIfRegistrationFails(): void
    {
        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            fn(string $endpoint) => $endpoint === 'context'
                ? ['salesChannel' => [
                    'countryId' => 'country-1',
                    'domains'   => [['url' => 'https://shop.example.com']],
                ]]
                : []
        );

        $result = $this->subject->createOrderAndGetDownloads('prod-123');
        self::assertSame([], $result);
    }


    /**
     * Scenario: Adding product to cart fails
     * Given cart response contains no lineItems
     * When createOrderAndGetDownloads is called
     * Then an empty array is returned
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsEmptyArrayIfCartFails(): void
    {
        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            fn(string $endpoint) => $endpoint === 'context'
                ? ['salesChannel' => [
                    'countryId' => 'country-1',
                    'domains'   => [['url' => 'https://shop.example.com']],
                ]]
                : ($endpoint === 'checkout/cart/line-item'
                    ? ['lineItems' => []]
                    : [])
        );

        $result = $this->subject->createOrderAndGetDownloads('prod-123');
        self::assertSame([], $result);
    }



    /**
     * Scenario: Payment not marked as paid
     * Given Admin API responds with "open"
     * When createOrderAndGetDownloads is called
     * Then an empty array is returned
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsEmptyArrayIfPaymentNotPaid(): void
    {
        $orderId       = 'order-123';
        $transactionId = 'trans-789';

        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            fn(string $endpoint) => $endpoint === 'checkout/order'
                ? [
                    'id' => $orderId,
                    'transactions' => [['id' => $transactionId]],
                    'lineItems' => [[
                        'id' => 'li-1',
                        'downloads' => [[
                            'orderLineItemId' => 'li-1',
                            'media' => ['fileName' => 'file', 'fileExtension' => 'bin']
                        ]],
                    ]],
                ]
                : ['salesChannel' => ['countryId' => 'country-1','domains' => [['url' => 'https://shop.example.com']]]]
        );

        $this->apiServiceMock->method('fetchFromAdminApi')->willReturn(['technicalName' => 'open']);

        $result = $this->subject->createOrderAndGetDownloads('prod-123');
        self::assertSame([], $result);
    }

    /**
     * Scenario: Multiple downloads in order
     * Given multiple line items and downloads with media info
     * When createOrderAndGetDownloads is called
     * Then all files are returned with correct names and contents
     */
    #[Test]
    public function createOrderAndGetDownloadsReturnsMultipleFiles(): void
    {
        $orderId       = 'order-456';
        $transactionId = 'trans-000';
        $downloadId1 = 'dn-1';
        $downloadId2 = 'dn-2';

        $this->apiServiceMock->method('fetchFromApi')->willReturnCallback(
            function (
                string $endpoint,
                array $params = [],
                string $method = 'POST',
                int $cache = 0,
                bool $raw = false
            ) use ($orderId, $transactionId, $downloadId1, $downloadId2) {
                return match ($endpoint) {
                    'context' => [
                        'salesChannel' => [
                            'countryId' => 'country-1',
                            'domains'   => [['url' => 'https://shop.example.com']],
                        ],
                    ],
                    'account/register' => ['id' => 'cust-1'],
                    'checkout/cart/line-item' => ['lineItems' => [['id' => 'prod-123']]],
                    'checkout/order' => [
                        'id' => $orderId,
                        'transactions' => [['id' => $transactionId]],
                        'lineItems' => [
                            [
                                'id' => 'li-1',
                                'downloads' => [[
                                    'id' => $downloadId1,
                                    'media' => ['fileName' => 'Setup', 'fileExtension' => 'exe']
                                ]],
                            ],
                            [
                                'id' => 'li-2',
                                'downloads' => [[
                                    'id' => $downloadId2,
                                    'media' => ['fileName' => 'Readme', 'fileExtension' => 'txt']
                                ]],
                            ],
                        ],
                    ],
                    "order/download/$orderId/$downloadId1" => $raw ? 'EXE-binary' : [],
                    "order/download/$orderId/$downloadId2" => $raw ? 'TXT-binary' : [],
                    default => [],
                };
            }
        );


        $this->apiServiceMock->method('fetchFromAdminApi')->willReturn(['technicalName' => 'paid']);

        $result = $this->subject->createOrderAndGetDownloads('prod-123');

        self::assertCount(2, $result);
        self::assertArrayHasKey('Setup.exe', $result);
        self::assertArrayHasKey('Readme.txt', $result);
        self::assertSame('EXE-binary', $result['Setup.exe']);
        self::assertSame('TXT-binary', $result['Readme.txt']);
    }
}
