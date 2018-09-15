<?php

namespace App\TaxType;

use App\Model\OperationContext;
use Money\Converter;
use Money\Currency;
use Money\Money;

/**
 * Class FixedAmountTaxType.
 */
class FixedAmountTaxType implements TaxTypeInterface
{
    /**
     * @var Money
     */
    private $fixedTax;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * FixedAmountTaxType constructor.
     *
     * @param int|string $amount
     * @param string     $currencyCode
     * @param Converter  $converter
     */
    public function __construct($amount, string $currencyCode, Converter $converter)
    {
        $this->fixedTax = new Money($amount, new Currency($currencyCode));
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTax(Money $money, OperationContext $context): Money
    {
        if ($money->getCurrency()->equals($this->fixedTax->getCurrency())) {
            return $this->fixedTax;
        }

        return $this->converter->convert($this->fixedTax, $money->getCurrency(), Money::ROUND_UP);
    }
}
