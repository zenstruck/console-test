<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TestInput extends StringInput
{
    public function __construct(string $input, array $inputs)
    {
        parent::__construct($input);

        $this->setInteractive(false);

        if ($inputs) {
            $stream = \fopen('php://memory', 'r+', false);

            foreach ($inputs as $value) {
                \fwrite($stream, $value.\PHP_EOL);
            }

            \rewind($stream);

            $this->setStream($stream);
            $this->setInteractive(true);
        }

        if (true === $this->hasParameterOption(['--no-interaction', '-n'], true)) {
            $this->setInteractive(false);
        }
    }

    public function isDecorated(): bool
    {
        return true === $this->hasParameterOption(['--ansi'], true);
    }

    public function getVerbosity(): int
    {
        if (true === $this->hasParameterOption(['--quiet', '-q'], true)) {
            return OutputInterface::VERBOSITY_QUIET;
        }

        if ($this->hasParameterOption('-vvv', true) || $this->hasParameterOption('--verbose=3', true) || 3 === $this->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_DEBUG;
        }

        if ($this->hasParameterOption('-vv', true) || $this->hasParameterOption('--verbose=2', true) || 2 === $this->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERY_VERBOSE;
        }

        if ($this->hasParameterOption('-v', true) || $this->hasParameterOption('--verbose=1', true) || $this->hasParameterOption('--verbose', true) || $this->getParameterOption('--verbose', false, true)) {
            return OutputInterface::VERBOSITY_VERBOSE;
        }

        return OutputInterface::VERBOSITY_NORMAL;
    }
}
