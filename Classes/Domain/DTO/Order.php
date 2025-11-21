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

namespace Madj2k\ShopwareConnector\Domain\DTO;

/**
 * Class Order
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @see https://shopware.stoplight.io/docs/store-api/a020d67a79ed0-create-an-order-from-a-cart
 *
 * ! This is read-only !
 */
class Order extends AbstractDto
{

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }


    /**
     * @return string
     */
    public function getVersionId(): string
    {
        return $this->data['versionId'] ?? '';
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->data['orderNumber'] ?? '';
    }

    /**
     * @return string
     */
    public function getBillingAddressId(): string
    {
        return $this->data['billingAddressId'] ?? '';
    }


    /**
     * @return string
     */
    public function getBillingAddressVersionId(): string
    {
        return $this->data['billingAddressVersionId'] ?? '';
    }


    /**
     * @return string
     */
    public function getPrimaryOrderDeliveryId(): string
    {
        return $this->data['primaryOrderDeliveryId'] ?? '';
    }


    /**
     * @return string
     */
    public function getPrimaryOrderDeliveryVersionId(): string
    {
        return $this->data['primaryOrderDeliveryVersionId'] ?? '';
    }


    /**
     * @return string
     */
    public function getPrimaryOrderTransactionId(): string
    {
        return $this->data['primaryOrderTransactionId'] ?? '';
    }


    /**
     * @return string
     */
    public function getPrimaryOrderTransactionVersionId(): string
    {
        return $this->data['primaryOrderTransactionVersionId'] ?? '';
    }


    /**
     * @return string
     */
    public function getCurrencyId(): string
    {
        return $this->data['currencyId'] ?? '';
    }


    /**
     * @return string
     */
    public function getLanguageId(): string
    {
        return $this->data['languageId'] ?? '';
    }


    /**
     * @return string
     */
    public function getSalesChannelId(): string
    {
        return $this->data['salesChannelId'] ?? '';
    }


    /**
     * @return string
     */
    public function getOrderDateTime(): string
    {
        return $this->data['orderDateTime'] ?? '';
    }


    /**
     * @return string
     */
    public function getOrderdate(): string
    {
        return $this->data['orderDate'] ?? '';
    }


    /**
     * @return float
     */
    public function getAmountTotal(): float
    {
        return $this->data['amountTotal'] ?? 0.0;
    }


    /**
     * @return float
     */
    public function getAmountNet(): float
    {
        return $this->data['amountNet'] ?? 0.0;
    }


    /**
     * @return float
     */
    public function getPositionPrice(): float
    {
        return $this->data['positionPrice'] ?? 0.0;
    }


    /**
     * @return string
     */
    public function getTaxStatus(): string
    {
        return $this->data['taxStatus'] ?? '';
    }


    /**
     * @return array
     */
    public function getShippingCosts(): array
    {
        return $this->data['shippingCosts'] ?? [];
    }


    /**
     * @return float
     */
    public function getCurrencyFactor(): float
    {
        return $this->data['currencyFactor'] ?? 0.0;
    }


    /**
     * @return string
     */
    public function getDeeplinkCode(): string
    {
        return $this->data['deepLinkCode'] ?? '';
    }


    /**
     * @return string
     */
    public function getAffiliatecode(): string
    {
        return $this->data['affiliateCode'] ?? '';
    }


    /**
     * @return string
     */
    public function getCampaignCode(): string
    {
        return $this->data['campaignCode'] ?? '';
    }


    /**
     * @return string
     */
    public function getCustomerComment(): string
    {
        return $this->data['customerComment'] ?? '';
    }


    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->data['source'] ?? '';
    }


    /**
     * @return string
     */
    public function getTaxCalculationType(): string
    {
        return $this->data['taxCalculationType'] ?? '';
    }


    /**
     * @return array
     */
    public function getCustomFields(): array
    {
        return $this->data['customFields'] ?? [];
    }


    /**
     * @return string
     */
    public function getCreatedById(): string
    {
        return $this->data['createdById'] ?? '';
    }


    /**
     * @return string
     */
    public function getUpdatedById(): string
    {
        return $this->data['updatedById'] ?? '';
    }


    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->data['createdAt'] ?? '';
    }


    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->data['updatedAt'] ?? '';
    }


    /**
     * @return array
     */
    public function getStateMachineState(): array
    {
        return $this->data['stateMachineState'] ?? [];
    }


    /**
     * @return string|null
     */
    public function getStateMachineStateTechnicalName(): ?string
    {
        return $this->data['stateMachineState']['technicalName'] ?? null;
    }


    /**
     * @return array
     */
    public function getPrimaryOrderDelivery(): array
    {
        return $this->data['primaryOrderDelivery'] ?? [];
    }


    /**
     * @return array
     */
    public function getPrimaryOrderTransaction(): array
    {
        return $this->data['primaryOrderTransaction'] ?? [];
    }


    /**
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Customer
     */
    public function getOrderCustomer(): Customer
    {
        if (isset($this->data['orderCustomer'])) {
            return new Customer($this->data['orderCustomer']);
        }

        return new Customer();
    }


    /**
     * @return array
     */
    public function getCurrency(): array
    {
        return $this->data['currency'] ?? [];
    }


    /**
     * @return array
     */
    public function getLanguage(): array
    {
        return $this->data['language'] ?? [];
    }


    /**
     * @return array
     */
    public function getAddresses(): array
    {
        return $this->data['addresses'] ?? [];
    }


    /**
     * Returns the billingAddress
     *
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Address
     */
    public function getBillingAddress(): Address
    {
        if (isset($this->data['billingAddress'])) {
            return new Address($this->data['billingAddress']);
        }

        return new Address();
    }


    /**
     * @return array
     */
    public function getDeliveries(): array
    {
        return $this->data['deliveries'] ?? [];
    }



    /**
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->data['lineItems'] ?? [];
    }


    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->data['transactions'] ?? [];
    }


    /**
     * @return string|null
     */
    public function getFirstTransactionId(): ?string
    {
        return $this->data['transactions'][0]['id'] ?? null;
    }


    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->data['documents'] ?? [];
    }


    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->data['tags'] ?? [];
    }
}

