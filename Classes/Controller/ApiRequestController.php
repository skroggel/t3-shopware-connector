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
namespace Madj2k\ShopwareConnector\Controller;

use JetBrains\PhpStorm\NoReturn;
use Madj2k\ShopwareConnector\Order\DirectDownloads;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Madj2k\ShopwareConnector\Utilities\FilterUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use ZipArchive;

class ApiRequestController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \Madj2k\ShopwareConnector\Order\DirectDownloads
     */
    protected DirectDownloads $directDownloads;


    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService
     */
    protected ShopwareApiService $shopwareApiService;


    /**
     * @var array
     */
    protected array $mappings = [];




    /**
     * Constructor.
     *
     * @param \Madj2k\ShopwareConnector\Service\ShopwareApiService $shopwareApiService
     * @param \Madj2k\ShopwareConnector\Order\DirectDownloads $directDownloads
     */
    public function __construct(ShopwareApiService $shopwareApiService, DirectDownloads $directDownloads)
    {
        $this->shopwareApiService = $shopwareApiService;
        $this->directDownloads = $directDownloads;
    }


    /**
     * Do this before every action
     *
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception|\Throwable
    */
    public function initializeAction(): void
    {
        parent::initializeAction();

        // check proxy
        if (isset($this->settings['proxy'])) {

            $proxy = [];
            if (isset($this->settings['proxy']['http'])) {
                $proxy['http'] = $this->settings['proxy']['http'];
            }
            if (isset($this->settings['proxy']['https'])) {
                $proxy['https'] = $this->settings['proxy']['https'];
            }

            $this->shopwareApiService->setProxyConfig($proxy);
        }

    }


    /**
     * Add some assignments to all views
     *
     * @return void
     */
    public function initializeView(): void
    {
        // assign to all views!
        $this->view->assign('shopwareApiService', $this->shopwareApiService);
    }


    /**
     * shows resources of current page
     *
     * @param array $filters
     * @param array $categories
     * @param string $term
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function listAction(array $filters = [], array $categories = [], string $term = '', int $page = 1): ResponseInterface
    {
        $filteredResult = $this->shopwareApiService->fetchFromApi(
            'search',
            [
                'term' => $term,
                'filter' => array_merge(
                    FilterUtility::prepareShopwareFilters($filters),
                    FilterUtility::prepareShopwareFilters($categories, 'categoryTree', 'equalsAny')
                ),
            ],
        );

        $unfilteredResult = $this->shopwareApiService->fetchFromApi(
            'search',
        );

        $allCategories = $this->shopwareApiService->fetchFromApi(
            'category',
        );

        // get all available options based on active filters via current filtered results!
        $availableFilterOptions = [];
        if (isset($filteredResult['aggregations']['properties']['entities'])) {
            $availableFilterOptions = FilterUtility::getAvailableFilterOptions($filteredResult['aggregations']['properties']['entities']);
        }

        // get all filters independently of active filters!
        $allFilters = [];
        if (isset($unfilteredResult['aggregations']['properties']['entities'] )) {
            $allFilters = $unfilteredResult['aggregations']['properties']['entities'];
        }

        $paginator = new ArrayPaginator($filteredResult['elements'], $page, (int) $this->settings['itemsPerPage']);
        $pagination = new SimplePagination($paginator);
        $this->view->assign('paginator', $paginator);
        $this->view->assign('pagination', $pagination);

        $this->view->assign('term', $term);
        $this->view->assign('allFilters', $allFilters);
        $this->view->assign('activeFilters', $filters);
        $this->view->assign('availableFilterOptions', $availableFilterOptions);

        $this->view->assign('allCategories', $allCategories);
        $this->view->assign('activeCategories', $categories);

        return $this->htmlResponse();
    }


    /**
     * shows selected product
     *
     * @param string $productNumber
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function showAction(string $productNumber): ResponseInterface
    {

        // load product
        $this->shopwareApiService->setProxyConfig($this->settings['proxy']);
        $results = $this->shopwareApiService->fetchFromApi(
            'product',
            [
                'filter' => [
                    [
                        'type'  => 'equals',
                        'field' => 'productNumber',
                        'value' => $productNumber,
                    ]
                ],
                'associations' => [
                    'downloads' => [
                        'associations' => [
                            'media'  => []
                        ]
                    ],
                    'properties' => [
                        'associations' => [
                            'group'  => []
                        ]
                    ],
                    'manufacturer' => [

                    ],
                ],
            ],
        );

        if (! empty($results['elements'])) {
            $this->view->assign('product', $results['elements'][0]);
        }

        return $this->htmlResponse();
    }


    /**
     * Download
     *
     * @param string $productId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function downloadAction (string $productId): ResponseInterface
    {

        // init FE-session via a redirect in order to be able to store the context-token from shopware
        $frontendUser = $this->request->getAttribute('frontend.user');
        $frontendUser->setKey('ses', 'dummy', serialize('dummy'));
        $frontendUser->storeSessionData();

        return $this->redirect(
            'downloadExecute',
            null,
            null,
            ['productId' => $productId]
        );
    }


    /**
     * Streams a file from the Shopware API directly to the browser
     *
     * @param string $productId
     * @return void
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    #[NoReturn] public function downloadExecuteAction(string $productId): void
    {
        $files = $this->directDownloads->createOrderAndGetDownloads($productId, $this->settings);

        if (empty($files)) {
            header('HTTP/1.1 404 Not Found');
            echo 'No files available.';
            exit;
        }

        // Case 1: only one file
        if (count($files) === 1) {
            $filename = array_key_first($files);
            $content  = (string)reset($files);

            // Output-Buffer cleanup
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Content-Length: ' . strlen($content));
            header('Cache-Control: private, no-store, no-cache, must-revalidate');
            header('Pragma: no-cache');

            echo $content;
            exit;
        }

        // Case 2: multiple files
        $tmp    = tempnam(sys_get_temp_dir(), 'shopware_dl_');
        $zipPath = $tmp . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            header('HTTP/1.1 500 Internal Server Error');
            echo 'Could not create ZIP archive.';
            exit;
        }

        foreach ($files as $filename => $content) {
            $safeName = basename($filename) ?: 'file.bin';
            $zip->addFromString($safeName, (string)$content);
        }
        $zip->close();

        // Output-Buffer cleanup
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="downloads.zip"');
        header('Content-Length: ' . filesize($zipPath));

        readfile($zipPath);

        // cleanup
        @unlink($zipPath);
        @unlink($tmp);
        exit;
    }
}
