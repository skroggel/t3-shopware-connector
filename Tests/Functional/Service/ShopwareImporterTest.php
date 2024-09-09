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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\ShopwareConnector\Service\ShopwareImporter;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\Log\NullLogger;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ShopwareImporterTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareImporterTest extends FunctionalTestCase
{

    protected const FIXTURE_PATH = __DIR__ . '/ShopwareImporterTest/Fixtures/';

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/shopware_connector',
    ];


    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareImporter|null
     */
    protected ?ShopwareImporter $importer = null;


    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareApiService|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $shopwareApiService = null;


    /**
     * Set up the test case
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->shopwareApiService = $this->createMock(ShopwareApiService::class);
        $this->shopwareImporter = new ShopwareImporter($this->shopwareApiService);
        $this->shopwareImporter->setLogger(new NullLogger());
    }


    /**
     * @test
     *
     * Scenario: Import products correctly
     * Given the Shopware API returns a list of products
     * When the importer runs
     * Then the products should be imported into TYPO3
     */
    public function itImportsProductsCorrectly(): void
    {
        // Mock API response
        $apiResponse = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseProducts.yaml');
        $this->shopwareApiService->method('fetchFromApi')
            ->willReturn($apiResponse);

        // Execute the import
        $result = $this->shopwareImporter->executeImport();

        // Assert that the import was successful
        $this->assertTrue($result);

        // Check the database for the imported product by its product number
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_product');
        $row = $connection->select(
            ['*'],
            'tx_shopwareconnector_domain_model_product',
            ['product_number' => '12345']
        )->fetchAssociative();

        $this->assertNotEmpty($row);
        $this->assertEquals('Product 1', $row['name']);
        $this->assertEquals('12345', $row['product_number']);
    }


    /**
     * @test
     *
     * Scenario: Import many-to-many associations correctly
     * Given the Shopware API returns a product with categories
     * When the importer runs
     * Then the product and its categories should be correctly imported and linked
     */
    public function itImportsManyToManyAssociationsCorrectly(): void
    {
        // Mock API response for product with categories
        $apiResponse = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseWithAssociations.yaml');
        $this->shopwareApiService->method('fetchFromApi')
            ->willReturn($apiResponse);

        // Execute the import
        $result = $this->shopwareImporter->executeImport();

        // Assert the import was successful
        $this->assertTrue($result);

        // Check MM-table for correct relationships using product_number
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_product');
        $productRow = $connection->select(
            ['uid'],
            'tx_shopwareconnector_domain_model_product',
            ['product_number' => '12345']
        )->fetchAssociative();

        $productUid = $productRow['uid'];


        $mmConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_product_category_mm');
        $rows = $mmConnection->select(
            ['uid_foreign'],
            'tx_shopwareconnector_product_category_mm',
            ['uid_local' => $productUid]
        )->fetchAllAssociative();

        $this->assertCount(2, $rows); // Assert two categories were linked

        // Check the related categories by their uids
        $categoryConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_category');
        foreach ($rows as $row) {
            $categoryRow = $categoryConnection->select(
                ['name'],
                'tx_shopwareconnector_domain_model_category',
                ['uid' => $row['uid_foreign']]
            )->fetchAssociative();

            // Verify the category names
            $this->assertContains($categoryRow['name'], ['Category 1', 'Category 2']);
        }

        // Verify the counter field in both tables
        $productCount = $connection->select(
            ['categories'],
            'tx_shopwareconnector_domain_model_product',
            ['uid' => $productUid]
        )->fetchAssociative();
        $this->assertEquals(2, $productCount['categories']); // Two categories linked
    }


    /**
     * @test
     *
     * Scenario: Handle empty API response
     * Given the Shopware API returns no data
     * When the importer runs
     * Then no products should be imported into TYPO3
     */
    public function itHandlesEmptyApiResponses(): void
    {
        // Mock API with empty response
        $this->shopwareApiService->method('fetchFromApi')
            ->willReturn(['elements' => []]);

        // Execute the import
        $result = $this->shopwareImporter->executeImport();

        // Assert no data was imported
        $this->assertTrue($result);

        // Check the database is empty
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_product');
        $rows = $connection->select(
            ['*'],
            'tx_shopwareconnector_domain_model_product'
        )->fetchAllAssociative();

        $this->assertEmpty($rows);
    }


    /**
     * @test
     *
     * Scenario: Handle one-to-many associations correctly
     * Given the Shopware API returns a product with properties
     * When the importer runs
     * Then the product and its properties should be correctly imported and linked
     */
    public function itHandlesOneToManyAssociationsCorrectly(): void
    {
        // Mock API response for product with properties
        $apiResponse = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseWithOneToMany.yaml');
        $this->shopwareApiService->method('fetchFromApi')
            ->willReturn($apiResponse);

        // Execute the import
        $result = $this->shopwareImporter->executeImport();

        // Assert the import was successful
        $this->assertTrue($result);

        // Check if properties were correctly associated with product by product number
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_product');
        $productRow = $connection->select(
            ['uid'],
            'tx_shopwareconnector_domain_model_product',
            ['product_number' => '12345']
        )->fetchAssociative();

        $productUid = $productRow['uid'];
        $propertyConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_property');
        $propertyRows = $propertyConnection->select(
            ['name'],
            'tx_shopwareconnector_domain_model_property',
            ['product_uid' => $productUid]
        )->fetchAllAssociative();

        $this->assertNotEmpty($propertyRows);

        // Verify the property names
        foreach ($propertyRows as $propertyRow) {
            $this->assertContains($propertyRow['name'], ['Size', 'Color']);
        }
    }


    /**
     * @test
     *
     * Scenario: Handle API unavailability
     * Given the Shopware API is unavailable
     * When the importer runs
     * Then the importer should throw an exception
     */
    public function itHandlesApiUnavailability(): void
    {
        // Mock API to throw an exception (API unavailable)
        $this->shopwareApiService->method('fetchFromApi')
            ->willThrowException(new \Exception('API unavailable'));

        // Execute the import
        $result = $this->shopwareImporter->executeImport();

        // Assert the import was not successful
        $this->assertFalse($result);
    }


    /**
     * @test
     *
     * Scenario: convertType works as expected for different types
     * Given various data types to be converted
     * When the convertType method is called
     * Then the data should be correctly converted
     */
    public function itConvertsTypesCorrectly(): void
    {
        $this->assertEquals(1, $this->shopwareImporter->convertType(true, 'boolean'));
        $this->assertEquals(0, $this->shopwareImporter->convertType(false, 'boolean'));
        $this->assertEquals(1, $this->shopwareImporter->convertType(1, 'boolean'));
        $this->assertEquals(0, $this->shopwareImporter->convertType(0, 'boolean'));
        $this->assertEquals(1, $this->shopwareImporter->convertType(0, 'booleanInverted'));
        $this->assertEquals(0, $this->shopwareImporter->convertType(1, 'booleanInverted'));
        $this->assertEquals(123, $this->shopwareImporter->convertType('123', 'int'));
        $this->assertEquals(45.67, $this->shopwareImporter->convertType('45.67', 'float'));
        $this->assertEquals('2023-01-01 00:00:00', $this->shopwareImporter->convertType('2023-01-01', 'dateTime'));
        $this->assertEquals('test string', $this->shopwareImporter->convertType('test string', 'string'));
    }


    /**
     * @test
     *
     * Scenario: mapData works correctly
     * Given a valid mapping and data
     * When the mapData method is called
     * Then the data should be mapped correctly
     */
    public function itMapsDataCorrectly(): void
    {
        $mapping = [
            'fields' => [
                [
                    'apiField' => 'name',
                    'tableField' => 'name',
                    'type' => 'string'
                ],
                [
                    'apiField' => 'price',
                    'tableField' => 'price',
                    'type' => 'float'
                ],
            ]
        ];

        $data = [
            'name' => 'Product 1',
            'price' => '99.99'
        ];

        $mappedData = $this->shopwareImporter->mapData($data, $mapping);
        $this->assertEquals('Product 1', $mappedData['name']);
        $this->assertEquals(99.99, $mappedData['price']);
    }


    /**
     * @test
     *
     * Scenario: getValueFromPath works correctly
     * Given a nested array and a path
     * When the getValueFromPath method is called
     * Then the value should be correctly extracted
     */
    public function itGetsValueFromPathCorrectly(): void
    {
        $data = [
            'product' => [
                'details' => [
                    'name' => 'Product 1'
                ]
            ]
        ];

        $value = $this->shopwareImporter->getValueFromPath($data, 'product.details.name');
        $this->assertEquals('Product 1', $value);
    }


    /**
     * @test
     *
     * Scenario: Already imported objects are skipped based on checksum
     * Given an object that has already been imported
     * When the importer runs again with the same data
     * Then the object should not be re-imported
     */
    public function itSkipsAlreadyImportedObjectsBasedOnChecksum(): void
    {
        // Mock API response
        $apiResponse = Yaml::parseFile(self::FIXTURE_PATH . 'ApiResponseProducts.yaml');
        $this->shopwareApiService->method('fetchFromApi')
            ->willReturn($apiResponse);

        // Execute the first import
        $result = $this->shopwareImporter->executeImport();
        $this->assertTrue($result);

        // Get the checksum of the imported product
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_shopwareconnector_domain_model_product');
        $row = $connection->select(
            ['checksum'],
            'tx_shopwareconnector_domain_model_product',
            ['product_number' => '12345']
        )->fetchAssociative();

        $originalChecksum = $row['checksum'];

        // Run the import again with the same data
        $result = $this->shopwareImporter->executeImport();
        $this->assertTrue($result);

        // Assert that the checksum did not change
        $row = $connection->select(
            ['checksum'],
            'tx_shopwareconnector_domain_model_product',
            ['product_number' => '12345']
        )->fetchAssociative();

        $this->assertEquals($originalChecksum, $row['checksum']);
    }
}
