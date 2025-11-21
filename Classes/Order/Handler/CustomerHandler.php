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

use Madj2k\ShopwareConnector\Domain\DTO\Context;
use Madj2k\ShopwareConnector\Domain\DTO\Customer;
use Madj2k\ShopwareConnector\Event\Order\BeforeCustomerRegisterEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterCustomerRegisterEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class OrderCustomerHandler
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CustomerHandler
{

    public function __construct(
        protected ShopwareApiService $apiService,
        protected EventDispatcherInterface $eventDispatcher,
    ) {}


    /**
     * Registers a customer as regular user or guest
     *
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Context $contextDto
     * @param \Madj2k\ShopwareConnector\Domain\DTO\Customer $customerDto
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Customer|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function register(Context $contextDto, Customer $customerDto): ?Customer
    {
        if (
            ($contextDto->getCustomer())
            && ($contextDto->getCustomer()->getId())
        ){
            return $contextDto->getCustomer();
        }

        $this->eventDispatcher->dispatch(new BeforeCustomerRegisterEvent($contextDto, $customerDto));

        $registrationData = $customerDto->toApiRegistrationArray($contextDto);
        $result = $this->apiService->fetchFromApi('account/register', $registrationData);

        if (empty($result['id'])) {
            return null;
        }

        $customerDto = new Customer($result);

        $this->eventDispatcher->dispatch(new AfterCustomerRegisterEvent($customerDto));

        return $customerDto;
    }

}
