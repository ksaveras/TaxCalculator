<?php

namespace App\TaxTrigger;

use App\Model\OperationContext;
use Money\Converter;
use Money\Money;

/**
 * Class WeeklyOperationTrigger.
 */
class WeeklyOperationTrigger implements TaxTriggerInterface
{
    /**
     * @var OperationLimit[]
     */
    private $cachedLimits = [];

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var OperationLimit
     */
    private $defaultLimit;

    /**
     * WeeklyOperationTrigger constructor.
     *
     * @param OperationLimit $defaultLimit
     * @param Converter      $converter
     */
    public function __construct(OperationLimit $defaultLimit, Converter $converter)
    {
        $this->defaultLimit = $defaultLimit;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function logOperation(Money $money, OperationContext $context): ?Money
    {
        $limit = $this->getLimit($context);

        if ($limit->limitReached()) {
            return null;
        }

        $limit->reduceOperations();
        if (!$limit->getMoney()->getCurrency()->equals($money->getCurrency())) {
            $convertedMoney = $this->converter->convert($money, $limit->getMoney()->getCurrency(), Money::ROUND_UP);
            $limit->reduceMoney($convertedMoney);
        } else {
            $limit->reduceMoney($money);
        }

        if ($limit->getMoney()->isNegative()) {
            $overLimitMoney = $limit->getMoney()->negative();
            if (!$money->getCurrency()->equals($overLimitMoney->getCurrency())) {
                $overLimitMoney = $this->converter->convert($overLimitMoney, $money->getCurrency());
            }

            return $overLimitMoney;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function limitReached(OperationContext $context): bool
    {
        $limit = $this->getLimit($context);

        return $limit->limitReached();
    }

    /**
     * @return OperationLimit
     */
    private function buildInitialLimits(): OperationLimit
    {
        return clone $this->defaultLimit;
    }

    /**
     * @param OperationContext $context
     *
     * @return OperationLimit
     */
    private function getLimit(OperationContext $context): OperationLimit
    {
        $key = $this->buildKey($context);

        if (!isset($this->cachedLimits[$key])) {
            $this->cachedLimits[$key] = $this->buildInitialLimits();
        }

        return $this->cachedLimits[$key];
    }

    /**
     * @param OperationContext $context
     *
     * @return string
     */
    private function buildKey(OperationContext $context): string
    {
        $monday = new \DateTimeImmutable(sprintf('Monday this week %s', $context->getDate()->format('Y-m-d')));

        return sprintf('%s:%s', $monday->format('YW'), $context->getUser()->getId());
    }
}
