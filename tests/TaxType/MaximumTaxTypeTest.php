<?php

namespace App\Tests\TaxType;

use App\Model\OperationContext;
use App\TaxType\MaximumTaxType;
use App\TaxType\PercentTaxType;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class MaximumTaxTypeTest.
 */
class MaximumTaxTypeTest extends TestCase
{
    /**
     * @expectedException \App\Exception\MissingTaxException
     */
    public function testEmptyTaxCalculator(): void
    {
        $taxCalculator = new MaximumTaxType();

        $taxCalculator->calculateTax(Money::EUR(10000), $this->getOperationMock());
    }

    public function testTaxCalculator(): void
    {
        $taxCalculator = new MaximumTaxType();
        $taxCalculator->add(new PercentTaxType(0.06));
        $taxCalculator->add(new PercentTaxType(0.05));

        $taxMoney = $taxCalculator->calculateTax(Money::EUR(10000), $this->getOperationMock());

        $this->assertEquals(Money::EUR(6), $taxMoney);
    }

    /**
     * @return OperationContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getOperationMock()
    {
        $mock = $this->createMock(OperationContext::class);

        return $mock;
    }
}
