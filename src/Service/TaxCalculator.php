<?php

namespace App\Service;

use App\Model\OperationContext;
use App\TaxType\SupportedTypeInterface;
use App\TaxType\TaxTypeInterface;
use Money\Money;

/**
 * Class TaxCalculator.
 */
class TaxCalculator implements TaxCalculatorInterface
{
    /**
     * @var TaxTypeInterface[]
     */
    private $taxTypes = [];

    /**
     * @inheritdoc
     */
    public function add(TaxTypeInterface $taxType): TaxCalculatorInterface
    {
        $this->taxTypes[] = $taxType;

        return $this;
    }

    /**
     * @param Money            $money
     * @param OperationContext $context
     *
     * @return Money
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        $allTaxMoney = new Money(0, $money->getCurrency());

        foreach ($this->taxTypes as $taxStrategy) {
            if ($taxStrategy instanceof SupportedTypeInterface &&
                !$taxStrategy->supports($context)
            ) {
                continue;
            }

            $taxMoney = $taxStrategy->calculateTax($money, $context);
            $allTaxMoney = $allTaxMoney->add($taxMoney);
        }

        return $allTaxMoney;
    }
}
