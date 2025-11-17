<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Utilities;

/**
 * Class FilterUtility
 *
 * Utility for preparing Shopware Store-API filters.
 *
 * @author Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 */
class FilterUtility
{
    /**
     * Prepares an array of filter IDs for the Shopware API.
     *
     * Expects an associative array where both the keys and the values are IDs.
     * Returns an array suitable for use as a filter parameter in the Shopware API.
     *
     * Example input:
     * [
     *      'groupId1' => ['optionId1', 'optionId2'],
     *      'groupId2' => 'optionId3'
     * ]
     *
     * Example output (type = equalsAny):
     * [
     *      [
     *          'type' => 'equalsAny',
     *          'field' => 'properties.id',
     *          'value' => 'optionId1|optionId2|optionId3'
     *      ]
     * ]
     *
     * Example output (type = equals):
     * [
     *      ['type' => 'equals', 'field' => 'properties.id', 'value' => 'optionId1'],
     *      ['type' => 'equals', 'field' => 'properties.id', 'value' => 'optionId2'],
     *      ['type' => 'equals', 'field' => 'properties.id', 'value' => 'optionId3']
     * ]
     *
     * @param array<string, string|array<string>> $filters   Flat filter config grouped by property group IDs
     * @param string $field                                 Field to filter on (default: properties.id)
     * @param string $type                                  Filter type: "equals" (default) or "equalsAny"
     * @return array<int, array<string, mixed>>
     */
    public static function prepareShopwareFilters(
        array $filters,
        string $field = 'properties.id',
        string $type = 'equals'
    ): array {

        /** @var array<string> $optionIds */
        $optionIds = [];

        foreach ($filters as $groupId => $value) {
            if (is_array($value)) {
                $optionIds = array_merge($optionIds, $value);
            } elseif (!empty($value)) {
                $optionIds[] = $value;
            }
        }

        $optionIds = array_values(array_unique($optionIds));

        if (empty($optionIds)) {
            return [];
        }

        if ($type === 'equalsAny') {
            return [[
                'type'  => 'equalsAny',
                'field' => $field,
                'value' => implode('|', $optionIds),
            ]];
        }

        // Default: multiple equals filters
        $result = [];
        foreach ($optionIds as $id) {
            $result[] = [
                'type'  => 'equals',
                'field' => $field,
                'value' => $id,
            ];
        }

        return $result;
    }


    /**
     * Extracts all available property options from a filtered Shopware search result.
     *
     * Input is expected in the format returned by the Shopware Store-API
     * under `aggregations.properties.entities`.
     *
     * Example input:
     * [
     *   'aggregations' => [
     *     'properties' => [
     *       'entities' => [
     *         [
     *           'id' => 'group1',
     *           'options' => [
     *             ['id' => 'option1', 'name' => 'Red'],
     *             ['id' => 'option2', 'name' => 'Blue'],
     *           ]
     *         ]
     *       ]
     *     ]
     *   ]
     * ]
     *
     * Example output:
     * [
     *   'option1' => ['id' => 'option1', 'name' => 'Red'],
     *   'option2' => ['id' => 'option2', 'name' => 'Blue'],
     * ]
     *
     * @param array<string, mixed> $filteredResult
     * @return array<string, array<string, mixed>>
     */
    public static function getAvailableFilterOptions(array $filteredResult): array
    {
        $availableFilterOptions = [];

        if (isset($filteredResult['aggregations']['properties']['entities'])) {
            foreach ($filteredResult['aggregations']['properties']['entities'] as $availableFilters) {
                if (
                    isset($availableFilters['options'])
                    && isset($availableFilters['id'])
                ) {
                    foreach ($availableFilters['options'] as $options) {
                        $availableFilterOptions[$options['id']] = $options;
                    }
                }
            }
        }

        return $availableFilterOptions;
    }
}
