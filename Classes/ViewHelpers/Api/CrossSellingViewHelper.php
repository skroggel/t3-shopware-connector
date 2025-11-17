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

namespace Madj2k\ShopwareConnector\ViewHelpers\Api;

use Madj2k\ShopwareConnector\Service\ShopwareAPIService;
use Madj2k\ShopwareConnector\ViewHelpers\Api\AbstractViewHelper;

/**
 * ViewHelper to fetch cross-selling groups for a given product from Shopware API.
 *
 * Usage in Fluid:
 *   {sw:crossSelling(shopwareApiService: shopwareApiService, productId: product.id, swLanguageId: swLanguageId)}
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CrossSellingViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;


    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            'productId',
            'string',
            'The Shopware product ID for which cross-sellings should be fetched',
            true
        );
    }


    /**
     * Fetches cross-selling groups for the given product ID via Shopware API.
     *
     * @return array The cross-selling groups with related products
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    public function render(): array
    {
        /** @var ShopwareAPIService $shopwareApiService */
        $shopwareApiService = $this->arguments['shopwareApiService'];
        $productId = (string)$this->arguments['productId'];

        return $shopwareApiService->fetchFromApi(
            sprintf('product/%s/cross-selling', $productId),
        );
    }
}
