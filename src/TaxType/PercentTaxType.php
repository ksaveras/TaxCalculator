<?php

namespace App\TaxType;

use App\Model\OperationContext;
use Money\Money;

/**
 * Class PercentTaxType.
 */
class PercentTaxType implements TaxTypeInterface
{
    /**
     * @var float
     */
    private $percentTax;

    /**
     * PercentTaxType constructor.
     *
     * @param float $percentTax
     */
    public function __construct($percentTax)
    {
        if ($percentTax < 0) {
            throw new \InvalidArgumentException('Tax percent can not be negative');
        }

        $this->percentTax = $percentTax;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        return $money->multiply($this->percentTax / 100, Money::ROUND_UP);
    }
}
