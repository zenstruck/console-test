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
    private Application $application;
    private string $command;
    private array $inputs = [];
    private bool $catchExceptions = false;

    public function __construct(Application $application, string $command)
    {
        foreach ($application->all() as $name => $commandObject) {
            if ($command === \get_class($commandObject)) {
                $command = $name;

                break;
            }
        }

        $this->application = $application;
        $this->command = $command;
    }

    public static function for(Command $command, array $input = []): self
    {
        $application = new Application();
        $application->add($command);

        return (new self($application, $command->getName()))->withInput($input);
    }

    public function addArgument(string $value): self
    {
        $this->command .= \sprintf(' "%s"', $value);

        return $this;
    }

    /**
     * @param string|array|null $value
     */
    public function addOption(string $name, $value = null): self
    {
        $name = \sprintf('--%s', \ltrim($name, '-'));
        $value = $value ?? [null];

        foreach ((array) $value as $item) {
            $this->command .= " {$name}";

            if ($item) {
                $this->command .= \sprintf('="%s"', $item);
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

    public function throwExceptions(): self
    {
        $this->catchExceptions = false;

        return $this;
    }

    public function catchExceptions(): self
    {
        $this->catchExceptions = true;

        return $this;
    }

    public function execute(): TestOutput
    {
        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions($this->catchExceptions);

        $input = new StringInput($this->command);
        $input->setInteractive($this->inputs ? true : false);

        $tester = new ApplicationTester($this->application, $input);
        $tester->setInputs($this->inputs);

        return $tester->execute();
    }
}
