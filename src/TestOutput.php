<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TestOutput extends StreamOutput implements ConsoleOutputInterface
{
    private ?OutputInterface $error = null;
    private array $sections = [];

    public function __construct(bool $splitStreams, TestInput $input)
    {
        parent::__construct(
            \fopen('php://memory', 'w', false),
            $input->getVerbosity(),
            $input->isDecorated()
        );

        if ($splitStreams) {
            $this->error = new StreamOutput(\fopen('php://memory', 'w', false));
            $this->error->setFormatter($this->getFormatter());
            $this->error->setVerbosity($this->getVerbosity());
            $this->error->setDecorated($this->isDecorated());
        }
    }

    public function getErrorOutput(): OutputInterface
    {
        return $this->error ?? $this;
    }

    public function setErrorOutput(OutputInterface $error): void
    {
        $this->error = $error;
    }

    public function section(): ConsoleSectionOutput
    {
        return new ConsoleSectionOutput($this->getStream(), $this->sections, $this->getVerbosity(), $this->isDecorated(), $this->getFormatter());
    }

    public function getDisplay(): string
    {
        \rewind($this->getStream());

        return \stream_get_contents($this->getStream());
    }

    public function getErrorDisplay(): string
    {
        if (!$this->error instanceof StreamOutput) {
            return '';
        }

        \rewind($this->error->getStream());

        return \stream_get_contents($this->error->getStream());
    }
}
