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

namespace Madj2k\ShopwareConnector\Order\Handler;

use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Madj2k\ShopwareConnector\Domain\DTO\Context;
use Madj2k\ShopwareConnector\Event\Order\BeforeContextProvideEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterContextProvideEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderContextHandler
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContextHandler
{

    /**
     * @param \Madj2k\ShopwareConnector\Service\ShopwareApiService $apiService
     * @param \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        protected ShopwareApiService $apiService,
        protected EventDispatcherInterface $eventDispatcher,
    ) {}


    /**
     * Fetches or creates a Shopware-Context
     *
     * @return \Madj2k\ShopwareConnector\Domain\Dto\Context|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function provide(): ?Context
    {
        $contextDto = new Context();

        $this->eventDispatcher->dispatch(new BeforeContextProvideEvent($contextDto));

        $contextData = $this->apiService->fetchFromApi('context', [], 'GET');
        if (empty($contextData)) {
            return null;
        }

        $contextDto->setContextData($contextData);

        $this->eventDispatcher->dispatch(new AfterContextProvideEvent($contextDto));

        return $contextDto;
    }

}
