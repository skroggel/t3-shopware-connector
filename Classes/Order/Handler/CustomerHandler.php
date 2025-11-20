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
use Madj2k\ShopwareConnector\Event\Order\BeforeCustomerEnsureEvent;
use Madj2k\ShopwareConnector\Event\Order\AfterCustomerEnsureEvent;
use Madj2k\ShopwareConnector\Service\ShopwareApiService;
use Psr\EventDispatcher\EventDispatcherInterface;

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
            (!empty($contextDto->getCustomer()))
            && (isset($contextDto->getCustomer()['id']))
        ){
            $customerDto->setCustomerData($contextDto->getCustomer());
            return $customerDto;
        }

        $this->eventDispatcher->dispatch(new BeforeCustomerEnsureEvent($contextDto, $customerDto));

        $registrationData = $customerDto->toApiRegistrationArray($contextDto);
        $result = $this->apiService->fetchFromApi('account/register', $registrationData);

        if (empty($result['id'])) {
            return null;
        }

        $customerDto->setCustomerData($result);

        $this->eventDispatcher->dispatch(new AfterCustomerEnsureEvent($customerDto));

        return $customerDto;
    }

}
