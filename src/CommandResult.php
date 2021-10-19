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
    private TestOutput $output;

    /**
     * @internal
     */
    public function __construct(int $statusCode, TestOutput $output)
    {
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
