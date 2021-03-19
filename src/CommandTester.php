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
    private InputInterface $input;

    public function __construct(Command $command, InputInterface $input)
    {
        $this->command = $command;
        $this->input = $input;
    }

    public function execute(bool $splitStreams): CommandResult
    {
        $this->input->setInteractive(false);

        if ($this->inputs) {
            $this->input->setStream(self::createStream($this->inputs));
            $this->input->setInteractive(true);
        }

        if (true === $this->input->hasParameterOption(['--no-interaction', '-n'], true)) {
            $this->input->setInteractive(false);
        }

        $this->initOutput([
            'decorated' => true === $this->input->hasParameterOption(['--ansi'], true),
            'verbosity' => $this->verbosity(),
            'capture_stderr_separately' => $splitStreams,
        ]);

        $statusCode = $this->command->run($this->input, $this->output);

        return new CommandResult($statusCode, $this->getDisplay(), $splitStreams ? $this->getErrorOutput() : '');
    }

    private function verbosity(): int
    {
        if (true === $this->input->hasParameterOption(['--quiet', '-q'], true)) {
            return OutputInterface::VERBOSITY_QUIET;
        }

        if ($this->input->hasParameterOption('-vvv', true) || $this->input->hasParameterOption('--verbose=3', true) || 3 === $this->input->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_DEBUG;
        }

        if ($this->input->hasParameterOption('-vv', true) || $this->input->hasParameterOption('--verbose=2', true) || 2 === $this->input->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERY_VERBOSE;
        }

        if ($this->input->hasParameterOption('-v', true) || $this->input->hasParameterOption('--verbose=1', true) || $this->input->hasParameterOption('--verbose', true) || $this->input->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERBOSE;
        }

        return OutputInterface::VERBOSITY_NORMAL;
    }
}
