<?php

namespace App\TaxType;

use App\Exception\InvalidOperationContext;
use App\Model\OperationContext;
use App\Model\OperationType;
use App\Model\UserType;
use Money\Money;

/**
 * Class CashOutLegalType.
 */
class CashOutLegalType implements TaxTypeInterface, SupportedTypeInterface
{
    /**
     * @var TaxTypeInterface
     */
    private $taxType;

    /**
     * CashOutLegalType constructor.
     *
     * @param TaxTypeInterface $taxType
     */
    public function __construct(TaxTypeInterface $taxType)
    {
        $this->taxType = $taxType;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(OperationContext $context): bool
    {
        return $context->getOperationType()->is(OperationType::CASH_OUT) &&
                $context->getUser()->getType() instanceof UserType &&
                $context->getUser()->getType()->is(UserType::LEGAL());
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        if (!$this->supports($context)) {
            throw new InvalidOperationContext('Not supported operation type');
        }

        return $this->taxType->calculateTax($money, $context);
    }
}
