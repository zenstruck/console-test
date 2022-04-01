<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Zenstruck\Assert;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TestCommand
{
    private Application $application;
    private string $cli;

    /** @var string[] */
    private array $inputs = [];

    /** @var callable|class-string|null */
    private $expectedException;
    private ?string $expectedExceptionMessage = null;
    private bool $splitOutputStreams = false;

    private function __construct(Command $command, string $cli)
    {
        if (!$application = $command->getApplication()) {
            $application = new Application();
            $application->add($command);

            $command->setApplication($application);
        }

        $this->application = $application;
        $this->cli = $cli;
    }

    public static function for(Command $command): self
    {
        return new self($command, (string) $command->getName());
    }

    public static function from(Application $application, string $cli): self
    {
        foreach ($application->all() as $commandObject) {
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
     * @param string|string[]|null $value
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

    /**
     * @param string[] $inputs
     */
    public function withInput(array $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * Expect executing the command will throw this exception. Fails if not thrown.
     *
     * @param class-string|callable $expectedException string: class name of the expected exception
     *                                                 callable: uses the first argument's type-hint
     *                                                 to determine the expected exception class. When
     *                                                 exception is caught, callable is invoked with
     *                                                 the caught exception
     * @param string|null           $expectedMessage   Assert the caught exception message "contains"
     *                                                 this string
     */
    public function expectException($expectedException, ?string $expectedMessage = null): self
    {
        $this->expectedException = $expectedException;
        $this->expectedExceptionMessage = $expectedMessage;

        return $this;
    }

    public function execute(?string $cli = null): CommandResult
    {
        $autoExit = $this->application->isAutoExitEnabled();
        $catchExceptions = $this->application->areExceptionsCaught();
        $cli = $cli ? \sprintf('%s %s', $this->cli, $cli) : $this->cli;

        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);

        $status = $this->doRun(
            $input = new TestInput($cli, $this->inputs),
            $output = new TestOutput($this->splitOutputStreams, $input)
        );

        $this->application->setAutoExit($autoExit);
        $this->application->setCatchExceptions($catchExceptions);

        return new CommandResult($cli, $status, $output);
    }

    private function doRun(TestInput $input, TestOutput $output): int
    {
        $fn = fn() => $this->application->run($input, $output);

        if (!$this->expectedException) {
            return $fn();
        }

        Assert::that($fn)->throws($this->expectedException, $this->expectedExceptionMessage);

        return 1;
    }
}
