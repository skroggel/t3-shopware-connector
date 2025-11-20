<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Order\Handler;

use Madj2k\ShopwareConnector\Domain\DTO\Order;
use Madj2k\ShopwareConnector\Domain\DTO\OrderDto;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class DownloadHandler
 *
 * Lädt alle verfügbaren Download-Dateien einer Bestellung via API.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 */
class DownloadHandler
{

    /**
     * @param \Madj2k\ShopwareConnector\Service\ShopwareApiService $apiService
     */
    public function __construct(
        protected ShopwareApiService $apiService
    ) {}


    /**
     * Fetch all downloads of an order
     *
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Order $orderDto
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function getDownloads(Order $orderDto): array
    {
        $files = [];
        $orderId = $orderDto->getId();
        $lineItems = $orderDto->getLineItems() ?? [];

        foreach ($lineItems as $lineItem) {
            foreach ($lineItem['downloads'] ?? [] as $download) {
                $downloadId = $download['id'] ?? null;
                if (!$downloadId) {
                    continue;
                }

                // build file name
                $filename = $lineItem['id'] . '.bin';
                if (!empty($download['media']['fileName'])) {
                    $filename = $download['media']['fileName'];
                    if (!empty($download['media']['fileExtension'])) {
                        $filename .= '.' . $download['media']['fileExtension'];
                    }
                }

                // fetch file
                $fileContent = $this->apiService->fetchFromApi(
                    sprintf('order/download/%s/%s', $orderId, $downloadId),
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
