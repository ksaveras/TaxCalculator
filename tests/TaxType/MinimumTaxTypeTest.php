<?php

namespace App\Tests\TaxType;

use App\Model\OperationContext;
use App\TaxType\MinimumTaxType;
use App\TaxType\PercentTaxType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MinimumTaxTypeTest extends TestCase
{
    /**
     * @expectedException \App\Exception\MissingTaxException
     */
    public function testEmptyTaxCalculator()
    {
        $taxCalculator = new MinimumTaxType();

        $taxCalculator->calculateTax(Money::EUR(10000), $this->getOperationMock());
    }

    public function testTaxCalculator()
    {
        $taxCalculator = new MinimumTaxType();
        $taxCalculator->add(new PercentTaxType(0.06));
        $taxCalculator->add(new PercentTaxType(0.05));

        $taxMoney = $taxCalculator->calculateTax(Money::EUR(10000), $this->getOperationMock());

        $this->assertEquals(Money::EUR(5), $taxMoney);
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
