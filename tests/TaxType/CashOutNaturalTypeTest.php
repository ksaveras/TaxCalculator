<?php

namespace App\Tests\TaxType;

use App\Model\OperationContext;
use App\Model\OperationType;
use App\Model\User;
use App\Model\UserType;
use App\TaxType\CashOutNaturalType;
use App\TaxType\TaxTypeInterface;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class CashOutNaturalTypeTest.
 */
class CashOutNaturalTypeTest extends TestCase
{
    /**
     * @dataProvider getContextTestParams
     *
     * @param OperationContext $context
     * @param bool             $expected
     */
    public function testSupportedContext(OperationContext $context, bool $expected): void
    {
        $type = new CashOutNaturalType($this->getTaxTypeMock());

        $isSupported = $type->supports($context);

        $this->assertEquals($expected, $isSupported);
    }

    /**
     * @expectedException \App\Exception\InvalidOperationContext
     */
    public function testCalculationInvalidContext(): void
    {
        $taxMock = $this->createMock(TaxTypeInterface::class);
        $taxMock->expects($this->never())
            ->method('calculateTax');

        $type = new CashOutNaturalType($taxMock);

        $type->calculateTax(
            Money::EUR(100),
            new OperationContext(new User(), new \DateTime(), OperationType::CASH_IN())
        );
    }

    public function testTaxTypeCall(): void
    {
        $taxMock = $this->createMock(TaxTypeInterface::class);
        $taxMock->expects($this->once())
            ->method('calculateTax')
            ->willReturn(Money::EUR(0));

        $type = new CashOutNaturalType($taxMock);

        $type->calculateTax(
            Money::EUR(100),
            new OperationContext((new User())->setType(UserType::NATURAL()), new \DateTime(), OperationType::CASH_OUT())
        );
    }

    /**
     * @return array
     */
    public function getContextTestParams(): array
    {
        $params = [];

        $context = new OperationContext(new User(), new \DateTime(), OperationType::CASH_IN());
        $params[] = [
            $context,
            false,
        ];

        $context = new OperationContext(
            (new User())->setType(UserType::NATURAL()),
            new \DateTime(),
            OperationType::CASH_IN()
        );
        $params[] = [
            $context,
            false,
        ];

        $context = new OperationContext(
            (new User())->setType(UserType::LEGAL()),
            new \DateTime(),
            OperationType::CASH_IN()
        );
        $params[] = [
            $context,
            false,
        ];

        $context = new OperationContext(
            new User(),
            new \DateTime(),
            OperationType::CASH_OUT()
        );
        $params[] = [
            $context,
            false,
        ];

        $context = new OperationContext(
            (new User())->setType(UserType::NATURAL()),
            new \DateTime(),
            OperationType::CASH_OUT()
        );
        $params[] = [
            $context,
            true,
        ];

        $context = new OperationContext(
            (new User())->setType(UserType::LEGAL()),
            new \DateTime(),
            OperationType::CASH_OUT()
        );
        $params[] = [
            $context,
            false,
        ];

        return $params;
    }

    /**
     * @return TaxTypeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getTaxTypeMock()
    {
        $mock = $this->createMock(TaxTypeInterface::class);

        $mock->method('calculateTax')
            ->willReturn(Money::EUR(0));

        return $mock;
    }
}
