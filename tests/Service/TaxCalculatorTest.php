<?php

namespace App\Tests\Service;

use App\Model\OperationContext;
use App\Service\TaxCalculator;
use App\TaxType\CashInType;
use App\TaxType\PercentTaxType;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class TaxCalculatorTest.
 */
class TaxCalculatorTest extends TestCase
{
    public function testNoTaxTypesCalculator(): void
    {
        $calculator = new TaxCalculator();
        $taxMoney = $calculator->calculateTax(Money::EUR(10000), $this->getContextMock());

        $this->assertEquals(Money::EUR(0), $taxMoney);
    }

    /**
     * @dataProvider getCalculatorTestParams
     *
     * @param Money $money
     * @param array $taxTypes
     * @param Money $expected
     */
    public function testCalculator(Money $money, array $taxTypes, Money $expected): void
    {
        $calculator = new TaxCalculator();
        foreach ($taxTypes as $taxType) {
            $calculator->add($taxType);
        }

        $taxMoney = $calculator->calculateTax($money, $this->getContextMock());

        $this->assertEquals($expected, $taxMoney);
    }

    /**
     * @return array
     */
    public function getCalculatorTestParams(): array
    {
        $params = [];

        $params[] = [
            Money::EUR(100),
            [],
            Money::EUR(0),
        ];

        $params[] = [
            Money::EUR(100),
            [
                new PercentTaxType(1),
            ],
            Money::EUR(1),
        ];

        $params[] = [
            Money::EUR(100),
            [
                new PercentTaxType(1),
                new PercentTaxType(1),
            ],
            Money::EUR(2),
        ];

        $params[] = [
            Money::EUR(100),
            [
                new CashInType(new PercentTaxType(1)),
            ],
            Money::EUR(0),
        ];

        return $params;
    }

    /**
     * @return OperationContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getContextMock()
    {
        return $this->createMock(OperationContext::class);
    }
}
