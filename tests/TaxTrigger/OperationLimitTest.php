<?php

namespace App\Tests\TaxTrigger;

use App\TaxTrigger\OperationLimit;
use Money\Money;
use PHPUnit\Framework\TestCase;

class OperationLimitTest extends TestCase
{
    public function testInitialOperationLimit()
    {
        $limit = new OperationLimit(3, Money::EUR(10000));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(3, $limit->getOperations());

        $limit = new OperationLimit(0, Money::EUR(0));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(0, $limit->getOperations());
    }

    public function testReduceOperations()
    {
        $limit = new OperationLimit(1, Money::EUR(10000));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(1, $limit->getOperations());

        $limit->reduceOperations();
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(0, $limit->getOperations());

        $limit->reduceOperations();
        $this->assertTrue($limit->limitReached());
        $this->assertEquals(-1, $limit->getOperations());
    }

    public function testReduceMoney()
    {
        $limit = new OperationLimit(1, Money::EUR(10000));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(Money::EUR(10000), $limit->getMoney());

        $limit->reduceMoney(Money::EUR(5000));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(Money::EUR(5000), $limit->getMoney());

        $limit->reduceMoney(Money::EUR(5000));
        $this->assertFalse($limit->limitReached());
        $this->assertEquals(Money::EUR(0), $limit->getMoney());

        $limit->reduceMoney(Money::EUR(5000));
        $this->assertTrue($limit->limitReached());
        $this->assertEquals(Money::EUR(-5000), $limit->getMoney());
    }

    public function testReduceBoth()
    {
        $limit = new OperationLimit(0, Money::EUR(100));
        $this->assertFalse($limit->limitReached());

        $limit->reduceOperations();
        $limit->reduceMoney(Money::EUR(500));
        $this->assertTrue($limit->limitReached());
    }
}
