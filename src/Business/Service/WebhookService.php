<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Business\Repository\CustomerRepositoryInterface;
use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Receiver;
use CleverReachCore\Core\BusinessLogic\Receiver\Http\Proxy;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\DataAccess\CountryRepository;
use CleverReachCore\DataAccess\CountryStateRepository;
use CleverReachCore\DataAccess\CustomerGroupRepository;
use CleverReachCore\DataAccess\LanguageRepository;
use CleverReachCore\DataAccess\PaymentMethodRepository;
use CleverReachCore\DataAccess\SalesChannelRepository;
use CleverReachCore\DataAccess\SalutationRepository;
use Exception;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateEntity;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\System\Salutation\SalutationEntity;

/**
 * Class WebhookService
 *
 * @package CleverReachCore\Business\Service
 */
class WebhookService
{
    private CustomerRepositoryInterface $baseRepository;
    private SalutationRepository $salutationRepository;
    private LanguageRepository $languageRepository;
    private SalesChannelRepository $salesChannelRepository;
    private CountryRepository $countryRepository;
    private CountryStateRepository $countryStateRepository;
    private CustomerGroupRepository $customerGroupRepository;
    private PaymentMethodRepository $paymentMethodRepository;

    /**
     * Creates ReceiverService.
     *
     * @param CustomerRepositoryInterface $baseRepository
     * @param SalutationRepository $salutationRepository
     * @param LanguageRepository $languageRepository
     * @param SalesChannelRepository $salesChannelRepository
     * @param CountryRepository $countryRepository
     * @param CountryStateRepository $countryStateRepository
     * @param CustomerGroupRepository $customerGroupRepository
     * @param PaymentMethodRepository $paymentMethodRepository
     */
    public function __construct(
        CustomerRepositoryInterface $baseRepository,
        SalutationRepository $salutationRepository,
        LanguageRepository $languageRepository,
        SalesChannelRepository $salesChannelRepository,
        CountryRepository $countryRepository,
        CountryStateRepository $countryStateRepository,
        CustomerGroupRepository $customerGroupRepository,
        PaymentMethodRepository $paymentMethodRepository
    ) {
        $this->baseRepository = $baseRepository;
        $this->salutationRepository = $salutationRepository;
        $this->languageRepository = $languageRepository;
        $this->salesChannelRepository = $salesChannelRepository;
        $this->countryRepository = $countryRepository;
        $this->countryStateRepository = $countryStateRepository;
        $this->customerGroupRepository = $customerGroupRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Handles upsert receiver event.
     *
     * @param array $requestBody
     *
     * @return void
     * @throws Exception
     */
    public function handleUpsertReceiver(array $requestBody): void
    {
        /** @var Proxy $proxy */
        $proxy = ServiceRegister::getService(Proxy::CLASS_NAME);
        $receiver = $proxy->getReceiver($requestBody['condition'], ($requestBody['payload'])['pool_id']);
        $data = $this->toArray($receiver);

        $requestBody['event'] === 'receiver.updated' ?
            $this->update($data) :
            $this->create($data);
    }

    /**
     * @param Receiver $receiver
     *
     * @return array
     */
    private function toArray(Receiver $receiver): array
    {
        /** @var SalutationEntity $salutation */
        $salutation = $this->salutationRepository->findSalutationByKey($receiver->getSalutation()) ?
            $this->salutationRepository->findSalutationByKey($receiver->getSalutation()) :
            $this->salutationRepository->findDefaultSalutation();
        /** @var LanguageEntity $language */
        $language = $this->languageRepository->findLanguageByName($receiver->getLanguage()) ?
            $this->languageRepository->findLanguageByName($receiver->getLanguage()) :
            $this->languageRepository->getDefaultLanguage();
        /** @var SalesChannelEntity $salesChannel */
        $salesChannel = $this->salesChannelRepository->findSalesChannelByName($receiver->getShop()) ?
            $this->salesChannelRepository->findSalesChannelByName($receiver->getShop()) :
            $this->salesChannelRepository->getDefaultSalesChannel();
        /** @var CountryEntity $country */
        $country = $this->countryRepository->findCountryByName($receiver->getCountry()) ?
            $this->countryRepository->findCountryByName($receiver->getCountry()) :
            $this->countryRepository->getDefaultCountry();
        /** @var CountryStateEntity $countryState */
        $countryState = $this->countryStateRepository->findCountryStateByName($receiver->getState()) ?
            $this->countryStateRepository->findCountryStateByName($receiver->getState()) :
            $this->countryStateRepository->getDefaultCountryState();
        /** @var CustomerGroupEntity $groupId */
        $groupId = $this->customerGroupRepository->getDefaultCustomerGroup();
        /** @var PaymentMethodEntity $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->getDefaultPaymentMethod();

        $customerId = Uuid::randomHex();
        $customerAddressId = Uuid::randomHex();

        $defaultAddress = [
            'id' => $customerAddressId,
            'countryId' => $country->getId(),
            'countryStateId' => $countryState->getId(),
            'zipcode' => $receiver->getZip(),
            'city' => $receiver->getCity(),
            'street' => $receiver->getStreet(),
            'phoneNumber' => $receiver->getPhone(),
            'customerId' => $customerId,
            'salutationId' => $salutation->getId(),
            'firstName' => $receiver->getFirstName(),
            'lastName' => $receiver->getLastName(),
        ];

        return [
            'id' => $customerId,
            'salesChannelId' => $salesChannel->getId(),
            'company' => $receiver->getCompany(),
            'customerNumber' => ($receiver->getCustomerNumber() !== '' && $receiver->getCustomerNumber()) ?
                $receiver->getCustomerNumber() : '0',
            'email' => $receiver->getEmail(),
            'groupId' => $groupId->getId(),
            'totalSpent' => $receiver->getTotalSpent(),
            'languageId' => $language->getId(),
            'salutationId' => $salutation->getId(),
            'firstName' => $receiver->getFirstName(),
            'lastName' => $receiver->getLastName(),
            'orderCount' => $receiver->getOrderCount() ?? 0,
            'activated' => date(
                'Y-m-d H:i:s',
                $receiver->getActivated() ? $receiver->getActivated()->getTimestamp() : 0
            ),
            'registered' => date(
                'Y-m-d H:i:s',
                $receiver->getRegistered() ? $receiver->getRegistered()->getTimestamp() : 0
            ),
            'birthday' => date(
                'Y-m-d H:i:s',
                $receiver->getBirthday() ?
                    $receiver->getBirthday()->getTimestamp() : 0
            ),
            'defaultPaymentMethodId' => $paymentMethod->getId(),
            'defaultShippingAddress' => $defaultAddress,
            'defaultBillingAddress' => $defaultAddress,
        ];
    }

    /**
     * @param array $data
     *
     * @return void
     */
    private function update(array $data): void
    {
        /** @var CustomerEntity $oldReceiver */
        $customer = $this->baseRepository->getByEmail($data['email']);

        $data['id'] = $customer->getId();
        $data['defaultShippingAddress']['id'] = $customer->getDefaultShippingAddressId();
        $data['defaultShippingAddress']['customerId'] = $data['id'];
        $data['defaultBillingAddress']['id'] = $customer->getDefaultBillingAddressId();
        $data['defaultBillingAddress']['customerId'] = $data['id'];

        $this->baseRepository->update($data);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    private function create(array $data): void
    {
        $this->baseRepository->create($data);
    }
}
