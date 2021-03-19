<?php

namespace Zenstruck\Console\Test;

use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\VarDumper\VarDumper;

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
        PHPUnit::assertStringContainsString($expected, $this->output());

        return $this;
    }

    public function assertOutputNotContains(string $expected): self
    {
        PHPUnit::assertStringNotContainsString($expected, $this->output());

        return $this;
    }

    public function assertErrorOutputContains(string $expected): self
    {
        PHPUnit::assertStringContainsString($expected, $this->errorOutput());

        return $this;
    }

    public function assertErrorOutputNotContains(string $expected): self
    {
        PHPUnit::assertStringNotContainsString($expected, $this->errorOutput());

        return $this;
    }

    public function assertSuccessful(): self
    {
        return $this->assertStatusCode(0);
    }

    public function assertStatusCode(int $expected): self
    {
        PHPUnit::assertSame($expected, $this->statusCode());

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
