<?php

namespace App\TaxTrigger;

use App\Model\OperationContext;
use Money\Money;

/**
 * Interface TaxTriggerInterface.
 */
interface TaxTriggerInterface
{

    /**
     * @param Money            $money
     * @param OperationContext $context
     *
     * @return Money|null
     */
    public function logOperation(Money $money, OperationContext $context);

    /**
     * @param OperationContext $context
     *
     * @return bool
     */
    public function limitReached(OperationContext $context): bool;
}
