<?php

namespace App\TaxType;

use App\Model\OperationContext;

/**
 * Interface SupportedStrategyInterface.
 */
interface SupportedTypeInterface
{
    /**
     * @param OperationContext $context
     *
     * @return bool
     */
    public function supports(OperationContext $context): bool;
}
