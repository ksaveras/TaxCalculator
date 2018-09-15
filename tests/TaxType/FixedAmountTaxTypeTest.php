<?php

namespace App\Tests\TaxType;

use App\TaxType\FixedAmountTaxType;
use App\Model\OperationContext;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class FixedAmountTaxTypeTest.
 */
class FixedAmountTaxTypeTest extends TestCase
{
    /**
     * @dataProvider getValidationParams
     *
     * @param string|int $amount
     * @param string     $currency
     * @param Money      $money
     * @param Money      $expected
     *
     * @internal     param Money $fixedTax
     */
    public function testTaxStrategy($amount, $currency, $money, $expected): void
    {
        $taxCalculator = new FixedAmountTaxType($amount, $currency, $this->getConverter());
        $taxMoney = $taxCalculator->calculateTax($money, $this->getOperationMock());

        $this->assertEquals($expected, $taxMoney);
    }

    /**
     * @return array
     */
    public function getValidationParams(): array
    {
        $params = [];

        $params[] = [
            500,
            'EUR',
            Money::EUR(10000),
            Money::EUR(500),
        ];

        $params[] = [
            500,
            'EUR',
            Money::EUR(220000),
            Money::EUR(500),
        ];

        $params[] = [
            300,
            'EUR',
            Money::USD(10000),
            Money::USD(315),
        ];

        $params[] = [
            500,
            'USD',
            Money::EUR(1000),
            Money::EUR(477),
        ];

        $params[] = [
            500,
            'USD',
            Money::EUR(2000),
            Money::EUR(477),
        ];

        return $params;
    }

    /**
     * @return Converter
     */
    private function getConverter(): Converter
    {
        $exchange = new ReversedCurrenciesExchange(
            new FixedExchange(
                [
                    'EUR' => [
                        'USD' => 1.05,
                    ],
                ]
            )
        );

        return new Converter(new ISOCurrencies(), $exchange);
    }

    /**
     * @return OperationContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getOperationMock()
    {
        return $this->createMock(OperationContext::class);
    }
}
