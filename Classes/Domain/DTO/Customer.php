<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Domain\DTO;

/**
 * Class Customer
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @see http://localhost:8000/store-api/account/register
 */
class Customer extends AbstractDto
{


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }


    /**
     * Returns customerNumber
     *
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->data['customerNumber'] ?? '';
    }


    /**
     * Sets customerNumber
     *
     * @param string $customerNumber
     * @return void
     */
    public function setCustomerNumber(string $customerNumber): void
    {
        $this->data['customerNumber'] = $customerNumber;
    }


    /**
     * Returns salutationId
     *
     * @return string
     */
    public function getSalutationId(): string
    {
        return $this->data['salutationId'] ?? '';
    }


    /**
     * Sets salutationId
     *
     * @param string $salutationId
     * @return void
     */
    public function setSalutationId(string $salutationId): void
    {
        $this->data['salutationId'] = $salutationId;
    }


    /**
     * Returns firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->data['firstName'] ?? '';
    }


    /**
     * Sets firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->data['firstName'] = $firstName;
    }


    /**
     * Returns lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->data['lastName'] ?? '';
    }


    /**
     * Sets lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->data['lastName'] = $lastName;
    }


    /**
     * Returns company
     *
     * @return string
     */
    public function getCompany(): string
    {
        return $this->data['company'] ?? '';
    }


    /**
     * Sets company
     *
     * @param string $company
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->data['company'] = $company;
    }


    /**
     * Returns email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->data['email'] ?? '';
    }


    /**
     * Sets email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->data['email'] = $email;
    }


    /**
     * Returns password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->data['password'] ?? '';
    }


    /**
     * Sets password
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->data['password'] = $password;
    }


    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->data['title'] ?? '';
    }


    /**
     * Sets title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->data['title'] = $title;
    }


    /**
     * Returns vatIds
     *
     * @return array
     */
    public function getVatIds(): array
    {
        return $this->data['vatIds'] ?? [];
    }


    /**
     * Sets vatIds
     *
     * @param array $vatIds
     * @return void
     */
    public function setVatIds(array $vatIds): void
    {
        $this->data['vatIds'] = $vatIds;
    }


    /**
     * Returns affiliateCode
     *
     * @return string
     */
    public function getAffiliateCode(): string
    {
        return $this->data['affiliateCode'] ?? '';
    }


    /**
     * Sets affiliateCode
     *
     * @param string $affiliateCode
     * @return void
     */
    public function setAffiliateCode(string $affiliateCode): void
    {
        $this->data['affiliateCode'] = $affiliateCode;
    }


    /**
     * Returns campaignCode
     *
     * @return string
     */
    public function getCampaignCode(): string
    {
        return $this->data['campaignCode'] ?? '';
    }


    /**
     * Sets campaignCode
     *
     * @param string $campaignCode
     * @return void
     */
    public function setCampaignCode(string $campaignCode): void
    {
        $this->data['campaignCode'] = $campaignCode;
    }


    /**
     * Returns active
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->data['active'] ?? false;
    }


    /**
     * Sets active
     *
     * @param bool $active
     * @return void
     */
    public function setActive(bool $active): void
    {
        $this->data['active'] = $active;
    }


    /**
     * Returns acceptedDataProtection
     *
     * @return bool
     */
    public function getAcceptedDataProtection(): bool
    {
        return $this->data['acceptedDataProtection'] ?? false;
    }


    /**
     * Sets acceptedDataProtection
     *
     * @param bool $acceptedDataProtection
     * @return void
     */
    public function setAcceptedDataProtection(bool $acceptedDataProtection): void
    {
        $this->data['acceptedDataProtection'] = $acceptedDataProtection;
    }


    /**
     * Returns doubleOptInRegistration
     *
     * @return bool
     */
    public function getDoubleOptInRegistration(): bool
    {
        return $this->data['doubleOptInRegistration'] ?? false;
    }


    /**
     * Sets doubleOptInRegistration
     *
     * @param bool $doubleOptInRegistration
     * @return void
     */
    public function setDoubleOptInRegistration(bool $doubleOptInRegistration): void
    {
        $this->data['doubleOptInRegistration'] = $doubleOptInRegistration;
    }


    /**
     * Returns doubleOptInEmailSentDate
     *
     * @return string
     */
    public function getDoubleOptInEmailSentDate(): string
    {
        return $this->data['doubleOptInEmailSentDate'] ?? '';
    }


    /**
     * Sets doubleOptInEmailSentDate
     *
     * @param string $doubleOptInEmailSentDate
     * @return void
     */
    public function setDoubleOptInEmailSentDate(string $doubleOptInEmailSentDate): void
    {
        $this->data['doubleOptInEmailSentDate'] = $doubleOptInEmailSentDate;
    }


    /**
     * Returns doubleOptInConfirmDate
     *
     * @return string
     */
    public function getDoubleOptInConfirmDate(): string
    {
        return $this->data['doubleOptInConfirmDate'] ?? '';
    }


    /**
     * Sets doubleOptInConfirmDate
     *
     * @param string $doubleOptInConfirmDate
     * @return void
     */
    public function setDoubleOptInConfirmDate(string $doubleOptInConfirmDate): void
    {
        $this->data['doubleOptInConfirmDate'] = $doubleOptInConfirmDate;
    }


    /**
     * Returns hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->data['hash'] ?? '';
    }


    /**
     * Sets hash
     *
     * @param string $hash
     * @return void
     */
    public function setHash(string $hash): void
    {
        $this->data['hash'] = $hash;
    }


    /**
     * Returns guest
     *
     * @return bool
     */
    public function getGuest(): bool
    {
        return $this->data['guest'] ?? false;
    }


    /**
     * Sets guest
     *
     * @param bool $guest
     * @return void
     */
    public function setGuest(bool $guest): void
    {
        $this->data['guest'] = $guest;
    }


    /**
     * Returns firstLogin
     *
     * @return string
     */
    public function getFirstLogin(): string
    {
        return $this->data['firstLogin'] ?? '';
    }



    /**
     * Returns lastLogin
     *
     * @return string
     */
    public function getLastLogin(): string
    {
        return $this->data['lastLogin'] ?? '';
    }


    /**
     * Returns accountType
     *
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->data['accountType'] ?? '';
    }


    /**
     * Returns birthday
     *
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->data['birthday'] ?? '';
    }


    /**
     * Sets birthday
     *
     * @param string $birthday
     * @return void
     */
    public function setBirthday(string $birthday): void
    {
        $this->data['birthday'] = $birthday;
    }


    /**
     * Returns birthdayDay
     *
     * @return string
     */
    public function getBirthdayDay(): string
    {
        return $this->data['birthdayDay'] ?? '';
    }


    /**
     * Sets birthdayDay
     *
     * @param string $birthdayDay
     * @return void
     */
    public function setBirthdayDay(string $birthdayDay): void
    {
        $this->data['birthdayDay'] = $birthdayDay;
    }


    /**
     * Returns birthdayMonth
     *
     * @return string
     */
    public function getBirthdayMonth(): string
    {
        return $this->data['birthdayMonth'] ?? '';
    }


    /**
     * Sets birthdayMonth
     *
     * @param string $birthdayMonth
     * @return void
     */
    public function setBirthdayMonth(string $birthdayMonth): void
    {
        $this->data['birthdayMonth'] = $birthdayMonth;
    }


    /**
     * Returns birthdayYear
     *
     * @return string
     */
    public function getBirthdayYear(): string
    {
        return $this->data['birthdayYear'] ?? '';
    }


    /**
     * Sets birthdayYear
     *
     * @param string $birthdayYear
     * @return void
     */
    public function setBirthdayYear(string $birthdayYear): void
    {
        $this->data['birthdayYear'] = $birthdayYear;
    }


    /**
     * Returns lastOrderDate
     *
     * @return string
     */
    public function getLastOrderDate(): string
    {
        return $this->data['lastOrderDate'] ?? '';
    }


    /**
     * Returns orderCount
     *
     * @return int
     */
    public function getOrderCount(): int
    {
        return $this->data['orderCount'] ?? 0;
    }


    /**
     * Returns orderTotalAmount
     *
     * @return int
     */
    public function getOrderTotalAmount(): int
    {
        return $this->data['orderTotalAmount'] ?? 0;
    }


    /**
     * Returns reviewCount
     *
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->data['reviewCount'] ?? 0;
    }


    /**
     * Returns the defaultBillingAddress
     *
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Address
     */
    public function getDefaultBillingAddress(): Address
    {
        if (isset($this->data['defaultBillingAddress'])) {
            return new Address($this->data['defaultBillingAddress']);
        }

        return new Address();
    }


    /**
     * Returns the defaultShippingAddress
     *
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Address
     */
    public function getDefaultShippingAddress(): Address
    {
        if (isset($this->data['defaultShippingAddress'])) {
            return new Address($this->data['defaultShippingAddress']);
        }

        return new Address();
    }


    /**
     * Returns the activeBillingAddress
     *
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Address
     */
    public function getActiveBillingAddress(): Address
    {
        if (isset($this->data['activeBillingAddress'])) {
            return new Address($this->data['activeBillingAddress']);
        }

        return new Address();
    }


    /**
     * Returns the activeShippingAddress
     *
     * @return \Madj2k\ShopwareConnector\Domain\DTO\Address
     */
    public function getActiveShippingAddress(): Address
    {
        if (isset($this->data['activeShippingAddress'])) {
            return new Address($this->data['activeShippingAddress']);
        }

        return new Address();
    }


    /**
     * Return data for Shopware API-Request
     *
     * @param \Madj2k\ShopwareConnector\Domain\Dto\Context $contextDto
     * @return array
     */
    public function toApiRegistrationArray(Context $contextDto): array
    {

        $data = [
            'email' => $this->getEmail(),
            'salutationId' => $this->getSalutationId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'acceptedDataProtection' => $this->getAcceptedDataProtection(),
            'storefrontUrl' => $contextDto->getSalesChannelDomainUrl(),
            'billingAddress' => array_merge(
                $this->getDefaultBillingAddress()->_toArray(ignoreEmpty: true),
                [
                    'countryId' => $contextDto->getSalesChannelCountryId(),
                ]
            ),
            'shippingAddress' => array_merge(
                $this->getDefaultShippingAddress()->_toArray(ignoreEmpty: true),
                [
                    'countryId' => $contextDto->getSalesChannelCountryId(),
                ]
            ),
            'guest' => $this->getGuest(),
            'birthdayDay' => $this->getBirthdayDay(),
            'birthdayMonth' => $this->getBirthdayMonth(),
            'birthdayYear' => $this->getBirthdayYear(),
            'title' => $this->getTitle(),
            'affiliateCode' => $this->getAffiliateCode(),
            'campaignCode' => $this->getCampaignCode(),
            'accountType' => $this->getAccountType(),
            'company' => $this->getCompany(),
            'vatIds' => $this->getVatIds(),
        ];

        if (! $this->getGuest()) {
            $data['password'] = $this->getPassword();
        }

        return $data;
    }


    /**
     * Populate DTO with settings data using setters
     *
     * @param array $settings
     * @return void
     */
    public function populateGuestFromSettings(array $settings): void
    {
        $guestSettings = $settings['anonymousGuestUser'] ?? [];

        $this->setFirstName($guestSettings['firstName'] ?? 'Max');
        $this->setLastName($guestSettings['lastName'] ?? 'Mustermann');
        $this->setEmail($guestSettings['email'] ?? 'guest@example.com');

        $addressDto = new Address();
        $addressDto->setFirstName($guestSettings['firstName'] ?? 'Max');
        $addressDto->setLastName($guestSettings['lastName'] ?? 'Mustermann');
        $addressDto->setStreet($guestSettings['street'] ?? 'Musterstraße 1');
        $addressDto->setZipcode($guestSettings['zip'] ?? '12345');
        $addressDto->setCity($guestSettings['city'] ?? 'Musterstadt');
        $this->data['defaultBillingAddress'] = $this->data['defaultShippingAddress'] =  $addressDto->_toArray();

        $this->setGuest(true);
    }

}
