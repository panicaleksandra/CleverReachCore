<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

/**
 * Class PaymentMethodRepository
 *
 * @package CleverReachCore\DataAccess
 */
class PaymentMethodRepository
{
    private EntityRepositoryInterface $paymentMethodRepository;

    /**
     * Creates new repository
     *
     * @param EntityRepositoryInterface $paymentMethodRepository
     */
    public function __construct(EntityRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Returns default payment method
     *
     * @return PaymentMethodEntity
     */
    public function getDefaultPaymentMethod(): PaymentMethodEntity
    {
        return $this->paymentMethodRepository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
