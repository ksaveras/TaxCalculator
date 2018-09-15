<?php

namespace App\TaxType;

use App\Model\OperationContext;
use App\TaxTrigger\TaxTriggerInterface;
use Money\Money;

/**
 * Class TriggeredTaxType
 */
class TriggeredTaxType implements TaxTypeInterface
{
    /**
     * @var TaxTriggerInterface
     */
    private $limitTrigger;

    /**
     * @var TaxTypeInterface
     */
    private $taxType;

    /**
     * TriggeredTaxStrategy constructor.
     *
     * @param TaxTriggerInterface $taxTrigger
     * @param TaxTypeInterface     $taxType
     */
    public function __construct(TaxTriggerInterface $taxTrigger, TaxTypeInterface $taxType)
    {
        $this->limitTrigger = $taxTrigger;
        $this->taxType = $taxType;
    }

    /**
     * @param Money            $money
     * @param OperationContext $context
     *
     * @return Money
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        $overLimitMoney = $this->limitTrigger->logOperation($money, $context);

        if (!$this->limitTrigger->limitReached($context)) {
            return new Money(0, $money->getCurrency());
        }

        if ($overLimitMoney instanceof Money) {
            return $this->taxType->calculateTax($overLimitMoney, $context);
        }

        return $this->taxType->calculateTax($money, $context);
    }
}
