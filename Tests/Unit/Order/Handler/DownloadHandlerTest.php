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

use Madj2k\ShopwareConnector\Order\Handler\DownloadHandler;
use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderDownloadHandlerTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderDownloadHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Throwable
     */
    #[Test]
    public function getDownloadsReturnsExpectedFiles(): void
    {
        $orderId = 'test-order';
        $downloadId = 'test-download';
        $fileName = 'testfile';
        $fileExtension = 'pdf';
        $lineItemId = 'item-001';

        $orderData = [
            'id' => $orderId,
            'lineItems' => [
                [
                    'id' => $lineItemId,
                    'downloads' => [
                        [
                            'id' => $downloadId,
                            'media' => [
                                'fileName' => $fileName,
                                'fileExtension' => $fileExtension
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $orderDto = new Order($orderData);

        $expectedContent = 'PDF-DATA';

        $apiServiceMock = $this->createMock(ShopwareApiService::class);
        $apiServiceMock->method('fetchFromApi')
            ->with(sprintf('order/download/%s/%s', $orderId, $downloadId))
            ->willReturn($expectedContent);

        $subject = new DownloadHandler($apiServiceMock);

        $result = $subject->getDownloads($orderDto);

        $expectedKey = $fileName . '.' . $fileExtension;
        $this->assertArrayHasKey($expectedKey, $result);
        $this->assertSame($expectedContent, $result[$expectedKey]);
    }

}
