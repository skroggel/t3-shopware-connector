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

use Madj2k\ShopwareConnector\Exception;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Localization\Locale;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteLanguageAwareInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use Psr\Log\NullLogger;

/**
 * Class ShopwareApiServiceTest
 *
 * Functional tests for ShopwareApiService
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareApiServiceTest extends FunctionalTestCase
{

    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private FrontendInterface|MockObject $cacheMock;


    /**
     * @var \TYPO3\CMS\Core\Http\RequestFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private RequestFactory|MockObject $requestFactoryMock;


    /**
     * @var \TYPO3\CMS\Core\Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    private Registry|MockObject $registryMock;


    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService
     */
    private ShopwareApiService $subject;


    /**
     * Sets up the test environment
     *
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheMock = $this->createMock(FrontendInterface::class);
        $this->requestFactoryMock = $this->createMock(RequestFactory::class);
        $this->registryMock = $this->createMock(Registry::class);

        $this->subject = new ShopwareApiService(
            $this->cacheMock,
            $this->requestFactoryMock,
            $this->registryMock
        );

        $this->subject->setLogger(new NullLogger());
    }


    /**
     * Scenario: Fetch data from Store API with cache hit
     * Given cached response exists for API request
     * When fetchFromApi is called
     * Then the cached response should be returned without HTTP call
     */
    #[Test]
    public function fetchFromApiReturnsCachedResult(): void
    {
        $endpoint = 'product';
        $expected = ['result' => 'cached'];
        $parameters = [];

        $url = 'https://www.example.com/store-api/' . $endpoint;
        $cacheKey = sha1($url . json_encode($parameters));

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);

        $this->cacheMock->method('has')->with($cacheKey)->willReturn(true);
        $this->cacheMock->method('get')->with($cacheKey)->willReturn($expected);

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Fetch data from Store API without cache
     * Given no cached response exists
     * When fetchFromApi is called
     * Then an HTTP request should be performed and the response cached and returned
     */
    #[Test]
    public function fetchFromApiMakesHttpRequestAndCachesResponse(): void
    {
        $endpoint = 'category';
        $expected = ['data' => 'live'];
        $parameters = [];

        $url = 'https://www.example.com/store-api/' . $endpoint;
        $cacheKey = sha1($url . json_encode($parameters));

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        $this->cacheMock->method('has')->willReturn(false);
        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $this->cacheMock->expects(self::once())
            ->method('set')
            ->with($cacheKey, $expected, [], 3600);

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }



    /**
     * Scenario: Fetch raw body from Store API
     * Given returnRawBody flag is true
     * When fetchFromApi is called
     * Then the raw response body should be returned as string
     */
    #[Test]
    public function fetchFromApiReturnsRawBody(): void
    {
        $rawContent = 'some-binary-data';

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock($rawContent));

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchFromApi('download/456', [], 'GET', 0, true);
        self::assertIsString($result);
        self::assertEquals($rawContent, $result);
    }


    /**
     * Scenario: Cache must be disabled for checkout endpoints
     * Given the endpoint starts with "checkout/"
     * When fetchFromApi is called
     * Then no cache get/set is performed and the response is returned directly
     */
    #[Test]
    public function fetchFromApiDisablesCacheForCheckoutEndpoint(): void
    {
        $endpoint = 'checkout/cart';
        $expected = ['data' => 'noCache'];

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => 'testKey',
        ]);

        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        $this->cacheMock->expects(self::never())->method('get');
        $this->cacheMock->expects(self::never())->method('set');

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }

    /**
     * Scenario: Cache must be disabled for order endpoints
     * Given the endpoint starts with "order/"
     * When fetchFromApi is called
     * Then no cache get/set is performed and the response is returned directly
     */
    #[Test]
    public function fetchFromApiDisablesCacheForOrderEndpoint(): void
    {
        $endpoint = 'order/list';
        $expected = ['orders' => []];

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => 'testKey',
        ]);

        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        $this->cacheMock->expects(self::never())->method('get');
        $this->cacheMock->expects(self::never())->method('set');

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }

    /**
     * Scenario: Cache must be disabled for account endpoints
     * Given the endpoint starts with "account/"
     * When fetchFromApi is called
     * Then no cache get/set is performed and the response is returned directly
     */
    #[Test]
    public function fetchFromApiDisablesCacheForAccountEndpoint(): void
    {
        $endpoint = 'account/profile';
        $expected = ['profile' => ['name' => 'John Doe']];

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => 'testKey',
        ]);

        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        $this->cacheMock->expects(self::never())->method('get');
        $this->cacheMock->expects(self::never())->method('set');

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }

    /**
     * Scenario: Admin API requests must never be cached
     * Given fetchFromAdminApi is called
     * When a response is returned
     * Then no cache get/set is performed and the response is returned directly
     */
    #[Test]
    public function fetchFromAdminApiNeverCaches(): void
    {
        $endpoint = 'product/list';
        $expected = ['products' => []];

        $this->registryMock->method('get')->willReturn([
            'adminApiUrl' => 'https://www.example.com/api',
            'adminApiAccessId' => 'id123',
            'adminApiAccessSecret' => 'secret123',
        ]);

        $this->subject = $this->getMockBuilder(\Madj2k\ShopwareConnector\Service\ShopwareApiService::class)
            ->setConstructorArgs([$this->cacheMock, $this->requestFactoryMock, $this->registryMock])
            ->onlyMethods(['fetchAdminApiAuthToken', 'getSwLanguageId'])
            ->getMock();

        $this->subject->method('fetchAdminApiAuthToken')->willReturn('adminToken');
        $this->subject->method('getSwLanguageId')->willReturn('lang-id');

        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        $this->cacheMock->expects(self::never())->method('get');
        $this->cacheMock->expects(self::never())->method('set');

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchFromAdminApi($endpoint);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Missing registry config entry for adminApiUrl
     * Given the registry does not return an adminApiUrl
     * When fetchAdminApiAuthToken is called
     * Then a runtime exception is thrown due to invalid URL
     */
    #[Test]
    public function fetchAdminApiAuthTokenThrowsOnInvalidUrl(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->registryMock->method('get')->willReturn([]);

        $this->requestFactoryMock
            ->method('request')
            ->willThrowException(new \RuntimeException('Invalid URL'));

        $this->subject->fetchAdminApiAuthToken();
    }


    /**
     * Scenario: HTTP request throws a GuzzleException
     * Given the HTTP client throws a GuzzleException
     * When fetchFromApi is called
     * Then the exception should be passed through and logged
     */
    #[Test]
    public function fetchFromApiThrowsOnGuzzleException(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);

        $this->cacheMock->method('has')->willReturn(false);
        $this->requestFactoryMock->method('request')->willThrowException(new \RuntimeException('Connection error'));

        $this->subject->fetchFromApi('product');
    }

    /**
     * Scenario: Fetch Admin API token successfully
     * Given credentials are available
     * When fetchAdminApiAuthToken is called
     * Then the token should be extracted from the response and returned
     *
     * @throws \Throwable
     */
    #[Test]
    public function fetchAdminApiAuthTokenReturnsAccessToken(): void
    {
        $expectedToken = 'abcdef123456';

        $this->registryMock->method('get')->willReturn([
            'adminApiUrl' => 'https://www.example.com/api',
            'adminApiAccessId' => 'id123',
            'adminApiAccessSecret' => 'secret123',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode(['access_token' => $expectedToken])));

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $result = $this->subject->fetchAdminApiAuthToken();
        self::assertEquals($expectedToken, $result);
    }

    /**
     * Scenario: Admin API response does not contain access_token
     * Given a valid response without access_token
     * When fetchAdminApiAuthToken is called
     * Then an Exception should be thrown
     */
    #[Test]
    public function fetchAdminApiAuthTokenThrowsExceptionIfTokenMissing(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(1725892301);

        $this->registryMock->method('get')->willReturn([
            'adminApiUrl' => 'https://www.example.com/api',
            'adminApiAccessId' => 'id123',
            'adminApiAccessSecret' => 'secret123',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode(['no_token_here' => true])));

        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $this->subject->fetchAdminApiAuthToken();
    }


    /**
     * Scenario: API responds with a non-200 HTTP status code
     * Given the API returns a 500 error
     * When executeRequest is called
     * Then an Exception should be thrown indicating the error response
     */
    #[Test]
    public function executeRequestThrowsExceptionOnNon200StatusCode(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(1725891306);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(500);
        $responseMock->method('getReasonPhrase')->willReturn('Internal Server Error');

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);
        $this->cacheMock->method('has')->willReturn(false);
        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $this->subject->fetchFromApi('product');
    }


    /**
     * Scenario: API returns invalid JSON
     * Given the API returns an invalid JSON response
     * When executeRequest is called
     * Then an Exception should be thrown
     */
    #[Test]
    public function executeRequestThrowsExceptionOnInvalidJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(1725891305);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock('{invalid-json'));

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => '',
        ]);
        $this->cacheMock->method('has')->willReturn(false);
        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        $this->subject->fetchFromApi('product');
    }


    /**
     * Scenario: Context token is stored in FE user session
     * Given a frontend user exists
     * When setContextToken is called with a new token
     * Then the token is stored serialized in the session and retrievable
     */
    #[Test]
    public function setContextTokenStoresTokenInFrontendUserSession(): void
    {
        $feUserMock = $this->createMock(\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class);

        $feUserMock->expects(self::once())
            ->method('setKey')
            ->with('ses', ShopwareApiService::SESSION_KEY, serialize('newToken'));

        $feUserMock->expects(self::once())
            ->method('storeSessionData');

        // inject fake request with frontend.user
        $request = new \TYPO3\CMS\Core\Http\ServerRequest();
        $request = $request->withAttribute('frontend.user', $feUserMock);
        $GLOBALS['TYPO3_REQUEST'] = $request;

        $this->subject->setContextToken('newToken');
        self::assertEquals('newToken', $this->subject->getContextToken());
    }


    /**
     * Scenario: Context token is read from FE user session
     * Given a frontend user has a serialized token in session
     * When getContextToken is called
     * Then the unserialized token is returned
     */
    #[Test]
    public function getContextTokenReturnsTokenFromFrontendUserSession(): void
    {
        $token = 'sessionToken';

        $feUserMock = $this->createMock(\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class);
        $feUserMock->method('getKey')
            ->with('ses', ShopwareApiService::SESSION_KEY)
            ->willReturn(serialize($token));

        $request = new \TYPO3\CMS\Core\Http\ServerRequest();
        $request = $request->withAttribute('frontend.user', $feUserMock);
        $GLOBALS['TYPO3_REQUEST'] = $request;

        $result = $this->subject->getContextToken();
        self::assertEquals($token, $result);
    }


    /**
     * Scenario: Resetting context token
     * Given a token was set before
     * When resetContextToken is called
     * Then getContextToken should return empty string
     */
    #[Test]
    public function resetContextTokenClearsStoredValue(): void
    {
        $this->subject->setContextToken('oldToken');
        $this->subject->resetContextToken();

        self::assertSame('', $this->subject->getContextToken());
    }


    /**
     * Scenario: Setting same token twice does not rewrite session
     * Given a frontend user already has a token
     * When setContextToken is called with the same token again
     * Then setKey/storeSessionData are not called
     */
    #[Test]
    public function setContextTokenWithSameValueDoesNotRewriteSession(): void
    {
        $token = 'sameToken';

        $feUserMock = $this->createMock(\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class);
        $feUserMock->method('getKey')
            ->with('ses', ShopwareApiService::SESSION_KEY)
            ->willReturn(serialize($token));

        $feUserMock->expects(self::never())->method('setKey');
        $feUserMock->expects(self::never())->method('storeSessionData');

        $request = new \TYPO3\CMS\Core\Http\ServerRequest();
        $request = $request->withAttribute('frontend.user', $feUserMock);
        $GLOBALS['TYPO3_REQUEST'] = $request;

        // preload same token
        $this->subject->setContextToken($token);

        // call again with same token
        $this->subject->setContextToken($token);
    }

    /**
     * Scenario: API returns a new context token
     * Given the response contains a sw-context-token header
     * When fetchFromApi is called
     * Then the token is stored in the FE user session and available via getContextToken
     */
    #[Test]
    public function fetchFromApiStoresContextTokenFromResponse(): void
    {
        $endpoint = 'checkout/cart';
        $expected = ['data' => 'ok'];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));
        $responseMock->method('hasHeader')->with('sw-context-token')->willReturn(true);
        $responseMock->method('getHeader')->with('sw-context-token')->willReturn(['newCtxToken']);

        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => 'testKey',
        ]);
        $this->cacheMock->method('has')->willReturn(false);
        $this->requestFactoryMock->method('request')->willReturn($responseMock);

        // mock FE user
        $feUserMock = $this->createMock(\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class);
        $feUserMock->expects(self::once())
            ->method('setKey')
            ->with('ses', ShopwareApiService::SESSION_KEY, serialize('newCtxToken'));
        $feUserMock->expects(self::once())->method('storeSessionData');

        $siteLanguageMock = $this->createMock(SiteLanguage::class);
        $siteLanguageMock->method('getLocale')->willReturn(new Locale('de-DE'));

        $request = new \TYPO3\CMS\Core\Http\ServerRequest();
        $request = $request->withAttribute('frontend.user', $feUserMock)
            ->withAttribute('language', $siteLanguageMock);

        $GLOBALS['TYPO3_REQUEST'] = $request;

        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
        self::assertEquals('newCtxToken', $this->subject->getContextToken());
    }


    /**
     * Scenario: Existing context token is reused in next API call
     * Given a token was stored previously in the FE user session
     * When fetchFromApi is called again
     * Then the token is sent in the request headers as sw-context-token
     */
    #[Test]
    public function fetchFromApiUsesStoredContextTokenInHeaders(): void
    {
        $endpoint = 'checkout/confirm';
        $expected = ['data' => 'withToken'];

        // preload token in FE user session
        $feUserMock = $this->createMock(\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class);
        $feUserMock->method('getKey')
            ->with('ses', \Madj2k\ShopwareConnector\Service\ShopwareApiService::SESSION_KEY)
            ->willReturn(serialize('existingToken'));

        // mock site language with Locale object
        $siteLanguageMock = $this->createMock(\TYPO3\CMS\Core\Site\Entity\SiteLanguage::class);
        $siteLanguageMock->method('getLocale')->willReturn(new \TYPO3\CMS\Core\Localization\Locale('en-GB'));

        // build request with FE user and language
        $request = new \TYPO3\CMS\Core\Http\ServerRequest();
        $request = $request
            ->withAttribute('frontend.user', $feUserMock)
            ->withAttribute('language', $siteLanguageMock);
        $GLOBALS['TYPO3_REQUEST'] = $request;

        // registry configuration
        $this->registryMock->method('get')->willReturn([
            'apiUrl' => 'https://www.example.com/store-api',
            'apiKey' => 'testKey',
        ]);

        // response for the /language request
        $languageResponseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $languageResponseMock->method('getStatusCode')->willReturn(200);
        $languageResponseMock->method('getBody')->willReturn(
            $this->createStreamMock(json_encode([
                ['id' => 'lang-id', 'translationCode' => ['code' => 'en-GB']]
            ]))
        );

        // response for the actual API request
        $responseMock = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($this->createStreamMock(json_encode($expected)));

        // intercept both requests: first /language, second actual endpoint
        $callCount = 0;
        $this->requestFactoryMock->method('request')
            ->willReturnCallback(function ($url, $method, $options) use (&$callCount, $languageResponseMock, $responseMock, $endpoint) {
                $callCount++;
                if ($callCount === 1) {
                    // first call: must be /language
                    \PHPUnit\Framework\Assert::assertStringContainsString('language', $url);
                    return $languageResponseMock;
                }
                // second call: must be the actual endpoint, including context token header
                \PHPUnit\Framework\Assert::assertStringContainsString($endpoint, $url);
                \PHPUnit\Framework\Assert::assertEquals('existingToken', $options['headers']['sw-context-token']);
                return $responseMock;
            });

        $this->cacheMock->method('has')->willReturn(false);

        // execute test
        $result = $this->subject->fetchFromApi($endpoint);
        self::assertEquals($expected, $result);
    }


    /**
     * Creates a stream mock that returns the given string on getContents()
     *
     * @param string $contents
     * @return \Psr\Http\Message\StreamInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    private function createStreamMock(string $contents): \Psr\Http\Message\StreamInterface
    {
        $streamMock = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $streamMock ->method('__toString')->willReturn($contents);
        $streamMock->method('getContents')->willReturn($contents);

        return $streamMock;
    }
}
