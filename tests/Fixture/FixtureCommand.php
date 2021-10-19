<?php

namespace Zenstruck\Console\Test\Tests\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FixtureCommand extends Command
{
    protected static $defaultName = 'fixture:command';

    protected function configure(): void
    {
        $this
            ->setDescription('zenstruck/console-test command for tests')
            ->addArgument('arg1', InputArgument::OPTIONAL)
            ->addOption('opt1', null, InputOption::VALUE_NONE)
            ->addOption('opt2', null, InputOption::VALUE_REQUIRED)
            ->addOption('opt3', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY)
            ->addOption('throw', null, InputOption::VALUE_NONE)
            ->addOption('code', null, InputOption::VALUE_REQUIRED, '', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errOutput = $output->getErrorOutput();

        $output->writeln('Executing <info>command</info>...');
        $output->writeln("verbosity: {$output->getVerbosity()}");
        $output->writeln('decorated: '.($output->isDecorated() ? 'yes' : 'no'));
        $errOutput->writeln('Error output.');

        if ($input->getOption('throw')) {
            throw new \RuntimeException('Exception thrown!');
        }

        if ($arg1 = $input->getArgument('arg1')) {
            $output->writeln("arg1 value: {$arg1}");
        }

        if ($input->getOption('opt1')) {
            $output->writeln('opt1 option set');
        }

        if ($opt2 = $input->getOption('opt2')) {
            $output->writeln("opt2 value: {$opt2}");
        }

        foreach ($input->getOption('opt3') as $value) {
            $output->writeln("opt3 value: {$value}");
        }

        (new SymfonyStyle($input, $output))->success('Long link: https://github.com/zenstruck/console-test/blob/997ee1f66743342ffd9cd00a77613ebfa2efd2b8/src/CommandResult.php');

        $table = new Table($output->section());
        $table->addRow(['table row 1']);
        $table->render();
        $table->appendRow(['table row 2']);

        return (int) $input->getOption('code');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $value = $this->getHelper('question')->ask($input, $output, new Question('Arg1 value?'));

        $input->setArgument('arg1', $value);
    }
}
