<?php

namespace App\Tests\TaxType;

use App\Model\OperationContext;
use App\TaxTrigger\TaxTriggerInterface;
use App\TaxType\PercentTaxType;
use App\TaxType\TriggeredTaxType;
use Money\Money;
use PHPUnit\Framework\TestCase;

class TriggeredTaxTypeTest extends TestCase
{
    /**
     * @dataProvider getCalculationParams
     *
     * @param Money|null $overLimit
     * @param bool       $limitReached
     * @param Money      $money
     * @param Money      $expected
     */
    public function testTaxCalculator($overLimit, $limitReached, $money, $expected)
    {
        $type = new TriggeredTaxType(
            $this->getTriggerMock($overLimit, $limitReached),
            new PercentTaxType(0.1)
        );

        $taxMoney = $type->calculateTax($money, $this->getOperationMock());

        $this->assertEquals($expected, $taxMoney);
    }

    /**
     * @return array
     */
    public function getCalculationParams()
    {
        $params = [];

        $params[] = [
            null,
            false,
            Money::EUR(100),
            Money::EUR(0),
        ];

        $params[] = [
            null,
            true,
            Money::EUR(1000),
            Money::EUR(1),
        ];

        $params[] = [
            null,
            false,
            Money::EUR(10000),
            Money::EUR(0),
        ];

        $params[] = [
            Money::EUR(100),
            false,
            Money::EUR(1000),
            Money::EUR(0),
        ];

        $params[] = [
            Money::EUR(100),
            true,
            Money::EUR(1000),
            Money::EUR(1),
        ];


        return $params;
    }

    /**
     * @param Money|null $overLimit
     * @param bool       $limitReached
     *
     * @return TaxTriggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getTriggerMock($overLimit, $limitReached)
    {
        $mock = $this->createMock(TaxTriggerInterface::class);

        $mock->method('logOperation')
            ->willReturn($overLimit);

        $mock->method('limitReached')
            ->willReturn($limitReached);

        return $mock;
    }

    /**
     * @return OperationContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getOperationMock()
    {
        return $this->createMock(OperationContext::class);
    }
}
