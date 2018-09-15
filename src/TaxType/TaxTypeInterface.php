<?php

namespace App\TaxType;

use App\Model\OperationContext;
use Money\Money;

/**
 * Interface TaxTypeInterface
 */
interface TaxTypeInterface
{
    /**
     * @param Money            $money
     * @param OperationContext $context
     *
     * @return Money
     */
    public function calculateTax(Money $money, OperationContext $context): Money;
}
