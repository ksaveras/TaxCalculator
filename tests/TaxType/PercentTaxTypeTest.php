<?php

namespace App\Tests\TaxType;

use App\Model\OperationContext;
use App\TaxType\PercentTaxType;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class PercentTaxTypeTest.
 */
class PercentTaxTypeTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNegativePercentage(): void
    {
        new PercentTaxType(-0.1);
    }

    /**
     * @dataProvider getValidationParams
     *
     * @param int   $expected
     * @param float $percent
     * @param int   $amount
     */
    public function testPercentStrategy($expected, $percent, $amount): void
    {
        $taxCalculator = new PercentTaxType($percent);

        /** @var Money $money */
        $money = Money::EUR($amount);
        $taxMoney = $taxCalculator->calculateTax($money, $this->getOperationMock());

        $this->assertEquals($expected, (int) $taxMoney->getAmount());
        $this->assertEquals($money->getCurrency(), $taxMoney->getCurrency());
    }

    /**
     * @return array
     */
    public function getValidationParams(): array
    {
        $params = [];

        $params[] = [
            3,
            0.03,
            10000,
        ];

        $params[] = [
            5,
            0.05,
            10000,
        ];

        $params[] = [
            25,
            0.25,
            10000,
        ];

        $params[] = [
            1,
            0.03,
            3000,
        ];

        $params[] = [
            1,
            0.01,
            3000,
        ];

        return $params;
    }

    /**
     * @return OperationContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getOperationMock()
    {
        return $this->createMock(OperationContext::class);
    }
}
