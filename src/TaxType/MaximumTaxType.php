<?php

namespace App\TaxType;

use App\Exception\MissingTaxException;
use App\Model\OperationContext;
use Money\Money;

/**
 * Class MaximumTaxType.
 */
class MaximumTaxType implements TaxTypeInterface
{
    /**
     * @var TaxTypeInterface[]|array
     */
    private $taxTypes = [];

    /**
     * @param TaxTypeInterface $strategy
     */
    public function add(TaxTypeInterface $strategy): void
    {
        $this->taxTypes[] = $strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        if (0 === \count($this->taxTypes)) {
            throw new MissingTaxException('At least one tax type must be registered');
        }

        $taxes = [];

        foreach ($this->taxTypes as $taxType) {
            /* @var TaxTypeInterface $taxType */
            $taxes[] = $taxType->calculateTax($money, $context);
        }

        $maximum = array_reduce($taxes, function ($carry, $taxMoney) {
            /* @var Money $taxMoney */
            if (null === $carry) {
                return $taxMoney;
            }

            return $taxMoney->greaterThan($carry) ? $taxMoney : $carry;
        }, null);

        return $maximum;
    }
}
