<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\TesterTrait;

/**
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CommandTester
{
    use TesterTrait;

    private Command $command;
    private InputInterface $testInput;

    public function __construct(Command $command, InputInterface $input)
    {
        $this->command = $command;
        $this->testInput = $input;
    }

    public function execute(bool $splitStreams): CommandResult
    {
        $this->testInput->setInteractive(false);

        if ($this->inputs) {
            $this->testInput->setStream(self::createStream($this->inputs));
            $this->testInput->setInteractive(true);
        }

        if (true === $this->testInput->hasParameterOption(['--no-interaction', '-n'], true)) {
            $this->testInput->setInteractive(false);
        }

        $this->initOutput([
            'decorated' => true === $this->testInput->hasParameterOption(['--ansi'], true),
            'verbosity' => $this->verbosity(),
            'capture_stderr_separately' => $splitStreams,
        ]);

        $statusCode = $this->command->run($this->testInput, $this->output);

        return new CommandResult($statusCode, $this->getDisplay(), $splitStreams ? $this->getErrorOutput() : '');
    }

    private function verbosity(): int
    {
        if (true === $this->testInput->hasParameterOption(['--quiet', '-q'], true)) {
            return OutputInterface::VERBOSITY_QUIET;
        }

        if ($this->testInput->hasParameterOption('-vvv', true) || $this->testInput->hasParameterOption('--verbose=3', true) || 3 === $this->testInput->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_DEBUG;
        }

        if ($this->testInput->hasParameterOption('-vv', true) || $this->testInput->hasParameterOption('--verbose=2', true) || 2 === $this->testInput->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERY_VERBOSE;
        }

        if ($this->testInput->hasParameterOption('-v', true) || $this->testInput->hasParameterOption('--verbose=1', true) || $this->testInput->hasParameterOption('--verbose', true) || $this->testInput->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERBOSE;
        }

        return OutputInterface::VERBOSITY_NORMAL;
    }
}
