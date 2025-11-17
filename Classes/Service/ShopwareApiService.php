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
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

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
     * @const string
     */
    public const string SESSION_KEY = 'shopware_connector';


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
     * @var array
     */
    protected array $proxyConfig = [];


    /**
     * @var string
     */
    protected string $swLanguageId = '';


    /**
     * @var string
     */
    protected string $contextToken = '';


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
     * Do a request against the store API
     *
     * @param string $endpoint
     * @param array $parameters
     * @param string $method
     * @param int $cacheLifeTime
     * @param bool $returnRawBody
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function fetchFromApi(
        string $endpoint,
        array $parameters = [],
        string $method = 'POST',
        int $cacheLifeTime = 3600,
        bool $returnRawBody = false
    ): array|string {

        $url = rtrim($this->getApiUrl(), '/') . '/' .  trim($endpoint, '/');
        $headers = [
            'sw-access-key'  => $this->getApiToken(),
            'sw-context-token' => $this->getContextToken(),
        ];

        if (
            ($endpoint != 'language')
            && ($swLanguageId = $this->getSwLanguageId())
        ){
            $headers['sw-language-id'] = $swLanguageId;
        }

        if (
            str_starts_with($endpoint, 'order/')
            || str_starts_with($endpoint, 'checkout/')
            || str_starts_with($endpoint, 'account/')
        ) {
            $cacheLifeTime = 0;
        }

        return $this->executeRequest($url, $parameters, $method, $headers, $cacheLifeTime, $returnRawBody);
    }


    /**
     * Do a request against the admin API
     *
     * @param string $endpoint
     * @param array $parameters
     * @param string $method
     * @param int $cacheLifeTime
     * @param bool $returnRawBody
     * @return array|string
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function fetchFromAdminApi(
        string $endpoint,
        array $parameters = [],
        string $method = 'POST',
        int $cacheLifeTime = 0,
        bool $returnRawBody = false
    ): array|string {

        $url = rtrim($this->getAdminApiUrl(), '/') . '/' . trim($endpoint, '/');
        $headers = [
            'Authorization' => 'Bearer ' . $this->fetchAdminApiAuthToken(),
        ];

        if ($swLanguageId = $this->getSwLanguageId()) {
            $headers['sw-language-id'] = $swLanguageId;
        }

        return $this->executeRequest($url, $parameters, $method, $headers, $cacheLifeTime, $returnRawBody);
    }


    /**
     * Executes a request against the store API or admin API
     *
     * @param string $url
     * @param array $parameters
     * @param string $method
     * @param array $headers
     * @param int $cacheLifetime
     * @param bool $returnRawBody
     * @param string|null $cacheKey
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function executeRequest(
        string $url,
        array $parameters = [],
        string $method = 'POST',
        array $headers = [],
        int $cacheLifetime = 3600,
        bool $returnRawBody = false,
        ?string $cacheKey = null,
    ): array|string {
        $cacheKey = $cacheKey ?: sha1($url . json_encode($parameters));
        $this->logger->debug('Using cache key: ' . $cacheKey);
$cacheLifetime = 0;
        if (
            $this->cache->has($cacheKey)
            && $cacheLifetime > 0
            && !$this->getContextToken()
            && !$returnRawBody)
        {
            return $this->cache->get($cacheKey);
        }

        try {
            try {
                $options = [
                    'headers' => array_merge($headers, [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ]),
                    'proxy'   => $this->proxyConfig ?: null
                ];

                if ($method === 'GET') {
                    $options['query'] = $parameters ?: [];
                } else {
                    $options['body'] = json_encode($parameters ?: new \stdClass());
                }

                $response = $this->requestFactory->request($url, $method, $options);

                // do not truncate errors!
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {

                $fullLengthMessage = $e->getResponse()->getBody()->getContents();
                $className = get_class($e);

                throw new $className (
                    $fullLengthMessage,
                    $e->getRequest(),
                    $e->getResponse(),
                );
            }

            if (! in_array($response->getStatusCode(), [200, 204])) {
                throw new Exception(
                    sprintf(
                        'API returned an error: %s %s',
                        $response->getStatusCode(),
                        $response->getReasonPhrase()
                    ),
                    1725891306
                );
            }

            // extract context token!
            if ($response->hasHeader('sw-context-token')) {
                $this->setContextToken($response->getHeader('sw-context-token')[0]);
            }

            $data = [];
            if ($response->getStatusCode() === 200) {
                if ($returnRawBody) {
                    return (string)$response->getBody();
                }

                $data = json_decode($response->getBody()->getContents(), true);
            }

            if (!is_array($data)) {
                throw new Exception(
                    sprintf('Cannot decode data from API: %s', json_last_error_msg()),
                    1725891305
                );
            }

            $this->cache->set($cacheKey, $data, [], $cacheLifetime);
            return $data;

        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Error while accessing API: %s', $e->getMessage()));
            throw $e;
        }
    }


    /**
     * Retrieves an OAuth2 Bearer token from the Shopware Admin API.
     *
     * @return string
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function fetchAdminApiAuthToken(): string
    {
        $url = rtrim($this->getAdminApiUrl(), '/') . '/oauth/token';
        $this->logger->info('Fetching Admin API token from URL: ' . $url);

        $payload = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->getAdminApiAccessId(),
            'client_secret' => $this->getAdminApiAccessSecret(),
        ];

        try {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $this->requestFactory->request($url, 'POST', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($payload, JSON_THROW_ON_ERROR),
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                if (isset($data['access_token'])) {
                    $this->logger->debug('Received Admin API access token.');
                    return (string)$data['access_token'];
                }

                throw new Exception(
                    'Admin API token response did not contain an access_token.',
                    1725892301
                );
            }

            throw new Exception(
                sprintf(
                    'Admin API token request failed: %s %s',
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ),
                1725892302
            );

        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf('Error while fetching Admin API token: %s', $e->getMessage())
            );
            throw $e;
        }
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

        return isset($config['apiUrl']) ? (string)$config['apiUrl'] : '';
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

        return isset($config['apiKey']) ? (string)$config['apiKey'] : '';
    }


    /**
     * Retrieves the Admin API URL from the TYPO3 Core Registry.
     *
     * @return string
     */
    protected function getAdminApiUrl(): string
    {
        $config = $this->registry->get(
            AdminModuleController::REGISTRY_NAMESPACE,
            AdminModuleController::REGISTRY_KEY,
        );

        return isset($config['adminApiUrl']) ? (string)$config['adminApiUrl'] : '';

    }


    /**
     * Retrieves the Admin API Access ID from the TYPO3 Core Registry.
     *
     * @return string
     */
    protected function getAdminApiAccessId(): string
    {
        $config = $this->registry->get(
            AdminModuleController::REGISTRY_NAMESPACE,
            AdminModuleController::REGISTRY_KEY,
        );

        return isset($config['adminApiAccessId']) ? (string)$config['adminApiAccessId'] : '';
    }


    /**
     * Retrieves the Admin API Access Secret from the TYPO3 Core Registry.
     *
     * @return string
     */
    protected function getAdminApiAccessSecret(): string
    {
        $config = $this->registry->get(
            AdminModuleController::REGISTRY_NAMESPACE,
            AdminModuleController::REGISTRY_KEY,
        );

        return isset($config['adminApiAccessSecret']) ? (string)$config['adminApiAccessSecret'] : '';
    }


    /**
     * @param array $proxyConfig e.g. ['http' => 'http://proxy:port', 'https' => 'http://proxy:port']
     */
    public function setProxyConfig(array $proxyConfig): void
    {
        $this->proxyConfig = $proxyConfig;
    }


    /**
     * @return string
     */
    public function getContextToken(): string
    {

        if ($this->getFrontendUser()) {
            $data = $this->getFrontendUser()->getKey('ses', self::SESSION_KEY);
            if (
                (is_string($data))
                && ($token = unserialize($data))
            ){
                $this->contextToken = $token;
            }
        }

        return $this->contextToken ?? '';
    }


    /**
     * @param string $token
     * @return void
     */
    public function setContextToken(string $token): void
    {

        if (
            ($this->getFrontendUser())
            && ($this->getContextToken() !== $token)
        ){
            // We use type ses to store the data in the session
            $this->getFrontendUser()->setKey('ses', self::SESSION_KEY, serialize($token));
            $this->getFrontendUser()->storeSessionData();
        }

        $this->contextToken = $token;
    }


    /**
     * @return void
     */
    public function resetContextToken(): void
    {
        $this->contextToken = '';
    }


    /**
     * Get SwLanguageId
     *
     * @returns string
     * @throws \Throwable
     * @throws \Madj2k\ShopwareConnector\Exception
     */
    protected function getSwLanguageId (): string
    {

        if ($this->swLanguageId) {
            return $this->swLanguageId;
        }

        if ($request = $this->getRequest()) {

            /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $language */
            $language = $request->getAttribute('language');
            $languageCode = (string) $language->getLocale();

            $result = $this->fetchFromApi(
                'language',
                [
                    'filter' => [
                        [
                            'type' => 'equals',
                            'field'  => 'translationCode.code',
                            'value' => $languageCode
                        ]
                    ]
                ],
            );

            $this->swLanguageId = '';
            if (! empty($result['elements'])) {
                $this->swLanguageId = $result['elements'][0]['id'];
            }
        }
        return $this->swLanguageId;
    }


    /**
     * Get the FrontendUserAuthentication
     *
     * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication|null
     */
    public function getFrontendUser(): FrontendUserAuthentication|null
    {
        if ($request = $this->getRequest()) {
            return $request->getAttribute('frontend.user');
        }
        return null;
    }


    /**
     * Get the request object
     *
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    private function getRequest(): ServerRequestInterface|null
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }
}
