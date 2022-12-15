<?php

/*
 * This file is part of the zenstruck/console-test package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Console\Test;

use Zenstruck\Assert;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CommandResult
{
    private string $cli;
    private int $statusCode;
    private TestOutput $output;

    /**
     * @internal
     */
    public function __construct(string $cli, int $statusCode, TestOutput $output)
    {
        $this->cli = $cli;
        $this->statusCode = $statusCode;
        $this->output = $output;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function output(): string
    {
        return $this->output->getDisplay();
    }

    public function errorOutput(): string
    {
        return $this->output->getErrorDisplay();
    }

    public function assertOutputContains(string $expected): self
    {
        Assert::that($this->output())->contains($expected);

        return $this;
    }

    public function assertOutputNotContains(string $expected): self
    {
        Assert::that($this->output())->doesNotContain($expected);

        return $this;
    }

    public function assertErrorOutputContains(string $expected): self
    {
        Assert::that($this->errorOutput())->contains($expected);

        return $this;
    }

    public function assertErrorOutputNotContains(string $expected): self
    {
        Assert::that($this->errorOutput())->doesNotContain($expected);

        return $this;
    }

    public function assertSuccessful(): self
    {
        return $this->assertStatusCode(0);
    }

    public function assertStatusCode(int $expected): self
    {
        Assert::that($this->statusCode())->is($expected);

        return $this;
    }

    public function dump(): self
    {
        $summary = "CLI: {$this->cli}, Status: {$this->statusCode()}";
        $output = [
            $summary,
            $this->output(),
            $this->errorOutput(),
            $summary,
        ];

        \call_user_func(
            \function_exists('dump') ? 'dump' : 'var_dump',
            \implode("\n\n", \array_filter($output))
        );

        return $this;
    }

    public function dd(): void
    {
        $this->dump();
        exit(1);
    }
}
