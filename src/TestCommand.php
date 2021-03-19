<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TestCommand
{
    private Command $command;
    private string $cli;
    private array $inputs = [];
    private bool $splitOutputStreams = false;

    private function __construct(Command $command, string $cli)
    {
        if (!$command->getApplication()) {
            $command->setApplication(new Application());
        }

        $this->command = $command;
        $this->cli = $cli;
    }

    public static function for(Command $command): self
    {
        return new self($command, $command->getName());
    }

    public static function from(Application $application, string $cli): self
    {
        foreach ($application->all() as $name => $commandObject) {
            if ($cli === \get_class($commandObject)) {
                return self::for($commandObject);
            }
        }

        return new self($application->find(\explode(' ', $cli, 2)[0]), $cli);
    }

    public function splitOutputStreams(): self
    {
        $this->splitOutputStreams = true;

        return $this;
    }

    public function addArgument(string $value): self
    {
        $this->cli .= \sprintf(' "%s"', $value);

        return $this;
    }

    /**
     * @param string|array|null $value
     */
    public function addOption(string $name, $value = null): self
    {
        $name = 0 !== \mb_strpos($name, '-') ? "--{$name}" : $name;
        $value = $value ?? [null];

        foreach ((array) $value as $item) {
            $this->cli .= " {$name}";

            if ($item) {
                $this->cli .= \sprintf('="%s"', $item);
            }
        }

        return $this;
    }

    public function addInput(string $value): self
    {
        $this->inputs[] = $value;

        return $this;
    }

    public function withInput(array $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
    }

    public function execute(): CommandResult
    {
        $tester = new CommandTester($this->command, new StringInput($this->cli));
        $tester->setInputs($this->inputs);

        return $tester->execute($this->splitOutputStreams);
    }
}
