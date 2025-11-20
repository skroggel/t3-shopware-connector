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
namespace Madj2k\ShopwareConnector\ViewHelpers\Category;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class CategoryTreeViewHelper
 *
 * Transforms a flat category list (with id, parentId, level etc.)
 * into a hierarchical tree structure.
 *
 * Example input (simplified):
 * [
 *   ['id' => '1', 'parentId' => null, 'name' => 'Root'],
 *   ['id' => '2', 'parentId' => '1', 'name' => 'Child'],
 *   ['id' => '3', 'parentId' => '2', 'name' => 'Subchild'],
 * ]
 *
 * Example output:
 * [
 *   [
 *     'id' => '1',
 *     'name' => 'Root',
 *     '_children' => [
 *       [
 *         'id' => '2',
 *         'name' => 'Child',
 *         '_children' => [
 *           ['id' => '3', 'name' => 'Subchild', '_children' => []]
 *         ]
 *       ]
 *     ]
 *   ]
 * ]
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TreeViewHelper extends AbstractViewHelper
{
    /**
     * Registers the arguments for this ViewHelper.
     *
     * Requires a flat array of categories, usually provided
     * by the Shopware Store-API:
     * $categories = $result['elements'];
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'categories',
            'array',
            'Flat list of categories (each element must contain id and parentId).',
            true
        );
    }


    /**
     * Renders the hierarchical category tree.
     *
     * @return array<int, array<string, mixed>> Category tree with nested "_children" arrays
     */
    public function render(): array
    {
        /** @var array<int, array<string, mixed>> $categories */
        $categories = $this->arguments['categories'];
        return $this->buildTree($categories);
    }


    /**
     * Builds a hierarchical tree from a flat list using parentId relations.
     * The nested children are stored under the "_children" key.
     *
     * @param array<int, array<string, mixed>> $categories
     *   Flat category list, e.g. as returned by the Shopware API
     * @return array<int, array<string, mixed>>
     *   Hierarchical tree structure with nested "_children" arrays
     */
    protected function buildTree(array $categories): array
    {
        // Prepare indexed array and initialize "_children" for each category
        $indexed = [];
        foreach ($categories as $category) {
            $category['_children'] = [];
            $indexed[$category['id']] = $category;
        }

        // Link children to their parents
        $tree = [];
        foreach ($indexed as $id => &$node) {
            if (!empty($node['parentId']) && isset($indexed[$node['parentId']])) {
                $indexed[$node['parentId']]['_children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }
}
