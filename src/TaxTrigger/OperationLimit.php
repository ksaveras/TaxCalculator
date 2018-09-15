<?php

namespace App\TaxTrigger;

use Money\Money;

/**
 * Class OperationLimit.
 */
class OperationLimit
{
    /**
     * @var int
     */
    private $operations;

    /**
     * @var Money
     */
    private $money;

    /**
     * OperationLimit constructor.
     *
     * @param int   $operations
     * @param Money $money
     */
    public function __construct(int $operations, Money $money)
    {
        $this->operations = $operations;
        $this->money = $money;
    }

    /**
     * @return int
     */
    public function getOperations(): int
    {
        return $this->operations;
    }

    /**
     * @return OperationLimit
     */
    public function reduceOperations(): OperationLimit
    {
        $this->operations--;

        return $this;
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {
        return $this->money;
    }

    /**
     * @param Money $money
     *
     * @return OperationLimit
     */
    public function reduceMoney(Money $money): OperationLimit
    {
        $this->money = $this->money->subtract($money);

        return $this;
    }

    /**
     * @return bool
     */
    public function limitReached(): bool
    {
        return ($this->money->isNegative() || $this->operations < 0);
    }
}
