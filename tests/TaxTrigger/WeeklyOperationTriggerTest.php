<?php

namespace App\Tests\TaxTrigger;

use App\Model\OperationContext;
use App\Model\OperationType;
use App\Model\User;
use App\TaxTrigger\OperationLimit;
use App\TaxTrigger\WeeklyOperationTrigger;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Money\Money;
use PHPUnit\Framework\TestCase;

class WeeklyOperationTriggerTest extends TestCase
{
    public function testWeeklyTrigger()
    {
        $context = new OperationContext(
            (new User())->setId('1'),
            new \DateTime('2017-01-01'),
            OperationType::CASH_OUT()
        );

        $trigger = new WeeklyOperationTrigger(new OperationLimit(2, Money::EUR(10000)), $this->getConverter());
        $limitReached = $trigger->limitReached($context);

        $this->assertFalse($limitReached);
        $result = $trigger->logOperation(Money::EUR(5000), $context);
        $this->assertNull($result);

        $limitReached = $trigger->limitReached($context);
        $this->assertFalse($limitReached);

        $overLimit = $trigger->logOperation(Money::EUR(6000), $context);

        $limitReached = $trigger->limitReached($context);
        $this->assertTrue($limitReached);
        $this->assertEquals(Money::EUR(1000), $overLimit);

        $result = $trigger->logOperation(Money::EUR(6000), $context);
        $this->assertNull($result);
    }

    public function testWeeklyTriggerOtherCurrency()
    {
        $context = new OperationContext(
            (new User())->setId('1'),
            new \DateTime('2017-01-01'),
            OperationType::CASH_OUT()
        );

        $trigger = new WeeklyOperationTrigger(new OperationLimit(2, Money::EUR(10000)), $this->getConverter());
        $limitReached = $trigger->limitReached($context);

        $this->assertFalse($limitReached);
        $result = $trigger->logOperation(Money::USD(5000), $context);
        $this->assertNull($result);

        $limitReached = $trigger->limitReached($context);
        $this->assertFalse($limitReached);

        $overLimit = $trigger->logOperation(Money::USD(60000), $context);

        $limitReached = $trigger->limitReached($context);
        $this->assertTrue($limitReached);
        $this->assertEquals(Money::USD(54500), $overLimit);

        $result = $trigger->logOperation(Money::USD(6000), $context);
        $this->assertNull($result);
    }

    /**
     * @return Converter
     */
    private function getConverter()
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
}
