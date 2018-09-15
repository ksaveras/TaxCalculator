<?php

namespace App\Command;

use App\Model\OperationContext;
use App\Model\OperationType;
use App\Model\User;
use App\Model\UserType;
use App\Service\TaxCalculator;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessOperationCommand.
 */
class ProcessOperationCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:money:process')
            ->addArgument('file', InputArgument::REQUIRED, 'File input with money transactions')
            ->setDescription('Calculate taxes for each cash in or cash out operation.');
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($input->getArgument('file'))) {
            throw new InvalidArgumentException('Input file not found');
        }

        if (!is_readable($input->getArgument('file'))) {
            throw new InvalidArgumentException('File is not readable');
        }
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('file');

        $taxCalculator = $this->getContainer()->get(TaxCalculator::class);
        $moneyParser = $this->getContainer()->get(DecimalMoneyParser::class);
        $moneyFormatter = $this->getContainer()->get(DecimalMoneyFormatter::class);

        $fh = fopen($fileName, 'rb');

        if (false === $fh) {
            throw new \RuntimeException(sprintf('Could not open file "%s"', $fileName));
        }

        while ($csvLine = fgetcsv($fh)) {
            $user = new User();
            $user->setId($csvLine[1]);
            $user->setType(UserType::byValue($csvLine[2]));

            $context = new OperationContext($user, new \DateTime($csvLine[0]), OperationType::byValue($csvLine[3]));
            $money = $moneyParser->parse($csvLine[4], $csvLine[5]);

            $taxMoney = $taxCalculator->calculateTax($money, $context);

            $output->writeln($moneyFormatter->format($taxMoney));
        }

        fclose($fh);
    }
}
