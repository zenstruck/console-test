<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\VarDumper\VarDumper;
use Zenstruck\Assert;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class CommandResult
{
    private int $statusCode;
    private string $output;
    private string $errorOutput;

    /**
     * @internal
     */
    public function __construct(int $statusCode, string $output, string $errorOutput)
    {
        $this->statusCode = $statusCode;
        $this->output = $output;
        $this->errorOutput = $errorOutput;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function errorOutput(): string
    {
        return $this->errorOutput;
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
        VarDumper::dump("Status: {$this->statusCode()}");
        VarDumper::dump($this->output());
        VarDumper::dump($this->errorOutput());

        return $this;
    }

    public function dd(): void
    {
        $this->dump();
        exit(1);
    }
}
