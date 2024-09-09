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

namespace Madj2k\ShopwareConnector\Tests\Functional\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Symfony\Component\Yaml\Yaml;
use Psr\Log\NullLogger;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Class ShopwareApiService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareApiServiceTest extends FunctionalTestCase
{

    protected const FIXTURE_PATH = __DIR__ . '/ShopwareApiService/Fixtures/';


    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/shopware_connector',
    ];


    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService|null
     */
    protected ?ShopwareApiService $shopwareApiService = null;

    /**
     * @var FrontendInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cacheMock;


    /**
     * @var RequestFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestFactoryMock = null;


    /**
     * @var Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $registryMock = null;


    /**
     * Set up the test case
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the required services
        $this->cacheMock = $this->createMock(FrontendInterface::class);
        $this->requestFactoryMock = $this->createMock(RequestFactory::class);
        $this->registryMock = $this->createMock(Registry::class);

        // Inject the mocked services into the ShopwareApiService
        $this->shopwareApiService = new ShopwareApiService(
            $this->cacheMock,
            $this->requestFactoryMock,
            $this->registryMock
        );

        // Set a NullLogger to avoid real logging during tests
        $this->shopwareApiService->setLogger(new NullLogger());
    }


    /**
     * @test
     *
     * Scenario: Fetching products from the API works correctly and stores data in cache
     * Given the Shopware API returns a valid product list
     * When fetchFromApi is called
     * Then the products should be returned correctly and stored in the cache
     */
    public function itFetchesProductsCorrectlyAndStoresInCache(): void
    {
        // Load the mocked response from a YAML file
        $mockResponseContent = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseProducts.yaml');

        // Mock the API response
        $response = new Response(200, [], json_encode($mockResponseContent));

        // Mock the RequestFactory to return the mocked response
        $this->requestFactoryMock->method('request')
            ->willReturn($response);

        // Mock the cache behavior
        $this->cacheMock->expects($this->once())
            ->method('set');

        // Execute the fetchFromApi call
        $result = $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');

        // Assert that the API response is as expected
        $this->assertArrayHasKey('elements', $result);
        $this->assertCount(1, $result['elements']);
        $this->assertEquals('Product 1', $result['elements'][0]['translated']['name']);
        $this->assertEquals('12345', $result['elements'][0]['productNumber']);
    }


    /**
     * @test
     *
     * Scenario: Fetching products from the cache works correctly
     * Given the product data is available in the cache
     * When fetchFromApi is called
     * Then the cached data should be returned without making an API call
     */
    public function itFetchesProductsFromCache(): void
    {
        // Load the mocked response from a YAML file
        $mockResponseContent = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseProducts.yaml');

        // Mock the cache to return cached data
        $this->cacheMock->method('has')
            ->willReturn(true);

        $this->cacheMock->method('get')
            ->willReturn($mockResponseContent);

        // RequestFactory should not be called since the data is from cache
        $this->requestFactoryMock->expects($this->never())
            ->method('request');

        // Execute the fetchFromApi call
        $result = $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');

        // Assert that the cached data is as expected
        $this->assertArrayHasKey('elements', $result);
        $this->assertCount(1, $result['elements']);
        $this->assertEquals('Product 1', $result['elements'][0]['translated']['name']);
        $this->assertEquals('12345', $result['elements'][0]['productNumber']);
    }


    /**
     * @test
     *
     * Scenario: Fetching products from the API with the same parameters twice
     * Given the Shopware API is called with the same parameters twice
     * When fetchFromApi is called again with the same parameters
     * Then the second call should return the cached data instead of making a new API request
     */
    public function itFetchesDataFromCacheOnSecondApiCall(): void
    {
        // Load the mocked response from a YAML file
        $mockResponseContent = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseProducts.yaml');

        // Mock the API response
        $response = new Response(200, [], json_encode($mockResponseContent));

        // Mock the RequestFactory to return the mocked response on the first call
        $this->requestFactoryMock->expects($this->once()) // Ensure request is made only once
        ->method('request')
            ->willReturn($response);

        // Cache should be set after the first call
        $this->cacheMock->expects($this->once())
            ->method('set');

        // Mock the cache to return cached data on the second call
        $this->cacheMock->method('has')
            ->willReturnOnConsecutiveCalls(false, true); // First call will not hit the cache, second will
        $this->cacheMock->method('get')
            ->willReturn($mockResponseContent);

        // Execute the fetchFromApi call for the first time (fetches from API)
        $resultFirstCall = $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');

        // Execute the fetchFromApi call for the second time (fetches from cache)
        $resultSecondCall = $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');

        // Assert that the first and second results are the same
        $this->assertEquals($resultFirstCall, $resultSecondCall);

        // Assert that the data is as expected
        $this->assertArrayHasKey('elements', $resultSecondCall);
        $this->assertCount(1, $resultSecondCall['elements']);
        $this->assertEquals('Product 1', $resultSecondCall['elements'][0]['translated']['name']);
        $this->assertEquals('12345', $resultSecondCall['elements'][0]['productNumber']);
    }


    /**
     * @test
     *
     * Scenario: Handling server exceptions during API request
     * Given the Shopware API returns a server error
     * When fetchFromApi is called
     * Then an exception should be thrown with the full error body
     */
    public function itHandlesServerExceptionCorrectly(): void
    {
        // Mock the API response with a server error
        $response = new Response(500, [], 'Internal Server Error');
        $request = new Request('GET', '/product');

        // Mock the RequestFactory to throw the exception
        $this->requestFactoryMock->method('request')
            ->willThrowException(new RequestException('Error', $request, $response));

        // Expect an exception to be thrown during the fetch
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Error');

        // Execute the fetchFromApi call
        $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');
    }


    /**
     * @test
     *
     * Scenario: Handling invalid JSON responses
     * Given the Shopware API returns invalid JSON data
     * When fetchFromApi is called
     * Then an exception should be thrown indicating a JSON decoding error
     */
    public function itHandlesInvalidJsonResponse(): void
    {
        // Mock the API response with invalid JSON
        $response = new Response(200, [], '{invalid_json}');

        // Mock the RequestFactory to return the invalid response
        $this->requestFactoryMock->method('request')
            ->willReturn($response);

        // Expect an exception to be thrown due to JSON decode error
        $this->expectException(\Madj2k\ShopwareConnector\Exception::class);
        $this->expectExceptionMessage('Can not decode data from API');
        $this->expectExceptionCode(1725891305);

        // Execute the fetchFromApi call
        $this->shopwareApiService->fetchFromApi('/product', [], 'de-DE');
    }


    /**
     * @test
     *
     * Scenario: Fetching products with associations and caching
     * Given the Shopware API returns a product with categories
     * When fetchFromApi is called with associations
     * Then the product and its categories should be returned correctly and stored in cache
     */
    public function itFetchesProductsWithAssociationsAndStoresInCache(): void
    {
        // Load the mocked response from a YAML file
        $mockResponseContent = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseWithAssociations.yaml');

        // Mock the API response
        $response = new Response(200, [], json_encode($mockResponseContent));

        // Mock the RequestFactory to return the mocked response
        $this->requestFactoryMock->method('request')
            ->willReturn($response);

        // Mock the cache behavior
        $this->cacheMock->expects($this->once())
            ->method('set');

        // Execute the fetchFromApi call with associations
        $result = $this->shopwareApiService->fetchFromApi('/product', ['associations' => ['categories']], 'de-DE');

        // Assert that the API response includes product and categories
        $this->assertArrayHasKey('elements', $result);
        $this->assertCount(1, $result['elements']);
        $this->assertEquals('Product 1', $result['elements'][0]['translated']['name']);
        $this->assertCount(2, $result['elements'][0]['categories']);
        $this->assertEquals('Category 1', $result['elements'][0]['categories'][0]['translated']['name']);
        $this->assertEquals('Category 2', $result['elements'][0]['categories'][1]['translated']['name']);
    }

}
