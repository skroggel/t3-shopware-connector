<?php
declare(strict_types=1);

namespace Madj2k\ShopwareConnector\Domain\DTO;

/**
 * Class Customer
 *
 * Repräsentiert Kundendaten für die Shopware-API über ein zentrales Daten-Array.
 * Neutral – unabhängig davon, ob Gast oder registriert.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 */
class Customer
{

    /**
     * @var array
     */
    protected array $customerData = [];


    /**
     * @return array
     */
    public function getCustomerData(): array
    {
        return $this->customerData;
    }


    /**
     * @param array $data
     * @return void
     */
    public function setCustomerData(array $data): void
    {
        $this->customerData = $data;
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->customerData['id'] ?? '';
    }


    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->customerData['id'] = $id;
    }


    /**
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->customerData['customerNumber'] ?? '';
    }


    /**
     * @param string $customerNumber
     * @return void
     */
    public function setCustomerNumber(string $customerNumber): void
    {
        $this->customerData['customerNumber'] = $customerNumber;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->customerData['email'] ?? '';
    }


    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->customerData['email'] = $email;
    }


    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->customerData['company'] ?? '';
    }


    /**
     * @param string $company
     * @return void
     */
    public function setCompany(string $company): void
    {
        $this->customerData['company'] = $company;
    }


    /**
     * @return string
     */
    public function getSalutation(): string
    {
        return $this->customerData['salutation'] ?? '';
    }


    /**
     * @param string $salutation
     * @return void
     */
    public function setSalutation(string $salutation): void
    {
        $this->customerData['salutation'] = $salutation;
    }


    /**
     * @return string
     */
    public function getSalutationId(): string
    {
        return $this->customerData['salutationId'] ?? '';
    }


    /**
     * @param string $salutationId
     * @return void
     */
    public function setSalutationId(string $salutationId): void
    {
        $this->customerData['salutationId'] = $salutationId;
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->customerData['firstName'] ?? '';
    }


    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->customerData['firstName'] = $firstName;
    }


    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->customerData['lastName'] ?? '';
    }


    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->customerData['lastName'] = $lastName;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->customerData['title'] ?? '';
    }


    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->customerData['title'] = $title;
    }


    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->customerData['defaultBillingAddress']['street'] ?? '';
    }


    /**
     * @param string $street
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->customerData['defaultBillingAddress']['street'] = $street;
    }


    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->customerData['defaultBillingAddress']['zip'] ?? '';
    }


    /**
     * @param string $zip
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->customerData['defaultBillingAddress']['zip'] = $zip;
    }


    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->customerData['defaultBillingAddress']['city'] ?? '';
    }


    /**
     * @param string $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->customerData['defaultBillingAddress']['city'] = $city;
    }


    /**
     * @return bool
     */
    public function getIsGuest(): bool
    {
        return (bool) ($this->customerData['isGuest'] ?? false);
    }


    /**
     * @param bool $isGuest
     * @return void
     */
    public function setIsGuest(bool $isGuest): void
    {
        $this->customerData['isGuest'] = $isGuest;
    }


    /**
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->customerData['accountType'] ?? '';
    }


    /**
     * @param string $accountType
     * @return void
     */
    public function setAccountType(string $accountType): void
    {
        $this->customerData['accountType'] = $accountType;
    }



    /**
     * Return data for Shopware API-Request
     *
     * @param \Madj2k\ShopwareConnector\Domain\Dto\Context $contextDto
     * @return array
     */
    public function toApiRegistrationArray(Context $contextDto): array
    {
        return [
            'email' => $this->getEmail(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'guest' => $this->getIsGuest(),
            'billingAddress' => [
                'street' => $this->getStreet(),
                'zipcode' => $this->getZip(),
                'city' => $this->getCity(),
                'countryId' => $contextDto->getSalesChannelCountryId(),
            ],
            'storefrontUrl' => $contextDto->getSalesChannelDomainUrl(),
        ];
    }


    /**
     * Populate DTO with settings data
     *
     * @param array $settings
     * @return void
     */
    public function populateGuestFromSettings(array $settings): void
    {
        $guestSettings = $settings['anonymousGuestUser'] ?? [];

        $this->customerData['firstName'] = $guestSettings['firstName'] ?? 'Max';
        $this->customerData['lastName'] = $guestSettings['lastName'] ?? 'Mustermann';
        $this->customerData['email'] = $guestSettings['email'] ?? 'guest@example.com';
        $this->customerData['street'] = $guestSettings['street'] ?? 'Musterstraße 1';
        $this->customerData['zip'] = $guestSettings['zip'] ?? '12345';
        $this->customerData['city'] = $guestSettings['city'] ?? 'Musterstadt';
        $this->customerData['isGuest'] = true;
    }

}
