<?php

namespace Zenstruck\Console\Test\Assert;

use Zenstruck\Assert\Expectation;
use Zenstruck\Console\Test\TestCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @mixin Expectation
 */
final class CompletionExpectation
{
    private TestCommand $command;
    private Expectation $expectation;

    /**
     * @internal
     */
    public function __construct(TestCommand $command, Expectation $expectation)
    {
        $this->command = $command;
        $this->expectation = $expectation;
    }

    /**
     * @internal
     */
    public function __call(string $name, array $arguments): self // @phpstan-ignore-line
    {
        $this->expectation->{$name}(...$arguments);

        return $this;
    }

    public function back(): TestCommand
    {
        return $this->command;
    }
}
