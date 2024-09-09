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
namespace Madj2k\ShopwareConnector\Service;

use Madj2k\ShopwareConnector\Controller\AdminModuleController;
use Madj2k\ShopwareConnector\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;

/**
 * Class ShopwareApiService
 *
 * Service for handling Shopware API requests with caching.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareApiService implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    protected FrontendInterface $cache;


    /**
     * @var \TYPO3\CMS\Core\Http\RequestFactory
     */
    protected RequestFactory $requestFactory;


    /**
     * @var \TYPO3\CMS\Core\Registry
     */
    protected Registry $registry;


    /**
     * constructor
     *
     * @param \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $cache
     * @param \TYPO3\CMS\Core\Http\RequestFactory $requestFactory
     * @param \TYPO3\CMS\Core\Registry $registry
     */
    public function __construct(FrontendInterface $cache, RequestFactory $requestFactory, Registry $registry)
    {
        $this->cache = $cache;
        $this->requestFactory = $requestFactory;
        $this->registry = $registry;
    }


    /**
     * Retrieves data from the Shopware API with caching.
     *
     * @param string $endpoint The Shopware API endpoint
     * @param array $parameters Query parameters for the request.
     * @param string $swLanguageId The swLanguageId
     * @param int $cacheLifetime Cache lifetime in seconds.
     * @return array The response data.
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function fetchFromApi(string $endpoint, array $parameters = [], string $swLanguageId = '', int $cacheLifetime = 3600): array
    {
        $cacheKey = sha1($endpoint . json_encode($parameters));
        $this->logger->debug('Using cache key: ' . $cacheKey);

        // Try to get data from the cache
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        // Fetch data from Shopware API
        $url = $this->getApiUrl() . $endpoint;
        $this->logger->info('Using url: ' .$url);

        try {
            try {
                $response = $this->requestFactory->request($url, 'GET', [
                    'query' => $parameters,
                    'headers' => [
                        'sw-access-key' =>  $this->getApiToken(),
                        'sw-language_id' => $swLanguageId
                    ],
                ]);

            // do not truncate errors!
            } catch (\GuzzleHttp\Exception\ServerException $ex) {

                $exFactoryWithFullBody = new class('', $ex->getRequest()) extends \GuzzleHttp\Exception\RequestException {
                    public static function getResponseBodySummary(ResponseInterface $response): string
                    {
                        return $response->getBody()->getContents();
                    }
                };

                throw $exFactoryWithFullBody->create($ex->getRequest(), $ex->getResponse());
            }

            // Check status code
            if ($response->getStatusCode() == 200) {

                // Can data be decoded?
                if ($data = json_decode($response->getBody()->getContents(), true)) {

                    // Store the result in cache
                    $this->cache->set($cacheKey, $data, [], $cacheLifetime);
                    return $data;

                } else {
                    throw new Exception(
                        sprintf(
                            'Can not decode data from API: %s',
                            json_last_error_msg()
                        ),
                        1725891305
                    );
                }
            }

            throw new Exception(
                sprintf(
                    'API returned an error: %s %s',
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ),
                1725891306
            );

        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Error while accessing API: %s',
                    $e->getMessage()
                )
            );
            throw $e;
        }
    }


    /**
     * Retrieves the API token from the TYPO3 Core Registry.
     *
     * @return string
     */
    protected function getApiToken(): string
    {
        $config = $this->registry->get(
            AdminModuleController::REGISTRY_NAMESPACE,
            AdminModuleController::REGISTRY_KEY,
            );

        return (string)$config['apiKey'];
    }


    /**
     * Retrieves the API URL from the TYPO3 Core Registry.
     *
     * @return string
     */
    protected function getApiUrl(): string
    {
        $config = $this->registry->get(
            AdminModuleController::REGISTRY_NAMESPACE,
            AdminModuleController::REGISTRY_KEY,
        );

        return (string)$config['apiUrl'];
    }
}
