<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Tests\Unit\Utilities;

use Madj2k\ShopwareConnector\Utilities\FilterUtility;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FilterUtilityTest
 *
 * Unit tests for FilterUtility
 *
 * @author Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 */
class FilterUtilityTest extends UnitTestCase
{
    /**
     * Reset singleton instances automatically after each test
     *
     * @var bool
     */
    protected bool $resetSingletonInstances = true;


    /**
     * Scenario: Mixed filter structure with arrays and strings
     * Given a filter array with both nested arrays and single string values
     * When prepareShopwareFilters is called with default type
     * Then it returns a list of equals filters (one per optionId)
     */
    #[Test]
    public function prepareShopwareFiltersReturnsFlattenedEqualsFilters(): void
    {
        $filters = [
            'group1' => ['option1', 'option2'],
            'group2' => 'option3',
            'group3' => ['option4'],
        ];

        $expected = [
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option1'],
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option2'],
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option3'],
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option4'],
        ];

        $result = FilterUtility::prepareShopwareFilters($filters);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Mixed filter structure with arrays and strings
     * Given a filter array with both nested arrays and single string values
     * When prepareShopwareFilters is called with type equalsAny
     * Then it returns a single equalsAny filter with concatenated values
     */
    #[Test]
    public function prepareShopwareFiltersReturnsEqualsAnyFilter(): void
    {
        $filters = [
            'group1' => ['option1', 'option2'],
            'group2' => 'option3',
            'group3' => ['option4'],
        ];

        $expected = [
            [
                'type'  => 'equalsAny',
                'field' => 'properties.id',
                'value' => 'option1|option2|option3|option4',
            ]
        ];

        $result = FilterUtility::prepareShopwareFilters($filters, 'properties.id', 'equalsAny');
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Empty input
     * Given an empty filter array
     * When prepareShopwareFilters is called
     * Then it returns an empty array
     */
    #[Test]
    public function prepareShopwareFiltersReturnsEmptyArrayIfNoFiltersGiven(): void
    {
        $filters = [];
        $expected = [];

        $result = FilterUtility::prepareShopwareFilters($filters);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Duplicate filter options
     * Given filter options with duplicates across groups
     * When prepareShopwareFilters is called
     * Then it returns unique equals filters in first-seen order
     */
    #[Test]
    public function prepareShopwareFiltersRemovesDuplicateOptionIds(): void
    {
        $filters = [
            'group1' => ['option1', 'option2'],
            'group2' => ['option2', 'option3'],
            'group3' => 'option1',
            'group4' => '',
        ];

        $expected = [
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option1'],
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option2'],
            ['type' => 'equals', 'field' => 'properties.id', 'value' => 'option3'],
        ];

        $result = FilterUtility::prepareShopwareFilters($filters);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Duplicate filter options with equalsAny
     * Given filter options with duplicates across groups
     * When prepareShopwareFilters is called with type equalsAny
     * Then it returns a single equalsAny filter with unique values
     */
    #[Test]
    public function prepareShopwareFiltersRemovesDuplicateOptionIdsInEqualsAny(): void
    {
        $filters = [
            'group1' => ['option1', 'option2'],
            'group2' => ['option2', 'option3'],
            'group3' => 'option1',
            'group4' => '',
        ];

        $expected = [
            [
                'type'  => 'equalsAny',
                'field' => 'properties.id',
                'value' => 'option1|option2|option3',
            ]
        ];

        $result = FilterUtility::prepareShopwareFilters($filters, 'properties.id', 'equalsAny');
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Valid aggregation input
     * Given a filteredResult with property aggregations
     * When getAvailableFilterOptions is called
     * Then it returns all options indexed by id
     */
    #[Test]
    public function getAvailableFilterOptionsReturnsOptions(): void
    {
        $filteredResult = [
            'aggregations' => [
                'properties' => [
                    'entities' => [
                        [
                            'id' => 'group1',
                            'options' => [
                                ['id' => 'option1', 'name' => 'Red'],
                                ['id' => 'option2', 'name' => 'Blue'],
                            ]
                        ],
                        [
                            'id' => 'group2',
                            'options' => [
                                ['id' => 'option3', 'name' => 'Small'],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $expected = [
            'option1' => ['id' => 'option1', 'name' => 'Red'],
            'option2' => ['id' => 'option2', 'name' => 'Blue'],
            'option3' => ['id' => 'option3', 'name' => 'Small'],
        ];

        $result = FilterUtility::getAvailableFilterOptions($filteredResult);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: No aggregations present
     * Given an empty filteredResult
     * When getAvailableFilterOptions is called
     * Then it returns an empty array
     */
    #[Test]
    public function getAvailableFilterOptionsReturnsEmptyIfNoAggregations(): void
    {
        $filteredResult = [];

        $expected = [];

        $result = FilterUtility::getAvailableFilterOptions($filteredResult);
        self::assertEquals($expected, $result);
    }


    /**
     * Scenario: Entities without options
     * Given entities without the "options" key
     * When getAvailableFilterOptions is called
     * Then it ignores them
     */
    #[Test]
    public function getAvailableFilterOptionsIgnoresEntitiesWithoutOptions(): void
    {
        $filteredResult = [
            'aggregations' => [
                'properties' => [
                    'entities' => [
                        ['id' => 'group1'], // no options
                        ['id' => 'group2', 'options' => []], // empty options
                    ]
                ]
            ]
        ];

        $expected = [];

        $result = FilterUtility::getAvailableFilterOptions($filteredResult);
        self::assertEquals($expected, $result);
    }
}
