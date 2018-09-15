<?php

namespace App\Service;

use App\Model\OperationContext;
use App\TaxType\TaxTypeInterface;
use Money\Money;

/**
 * Class TaxCalculatorInterface.
 */
interface TaxCalculatorInterface
{
    /**
     * @param Money            $money
     * @param OperationContext $context
     *
     * @return Money
     */
    public function calculateTax(Money $money, OperationContext $context): Money;

    /**
     * @param TaxTypeInterface $taxType
     *
     * @return TaxCalculatorInterface
     */
    public function add(TaxTypeInterface $taxType): TaxCalculatorInterface;
}
