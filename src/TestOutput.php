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

    /** @var ConsoleSectionOutput[] */
    private array $sections = [];

    public function __construct(bool $splitStreams, TestInput $input)
    {
        if (!$stream = \fopen('php://memory', 'w', false)) {
            throw new \RuntimeException('Failed to open stream.');
        }

        parent::__construct($stream, $input->getVerbosity(), $input->isDecorated());

        if ($splitStreams) {
            if (!$stream = \fopen('php://memory', 'w', false)) {
                throw new \RuntimeException('Failed to open stream.');
            }

            $this->error = new StreamOutput($stream);
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

    /**
     * @param bool $decorated
     */
    public function setDecorated($decorated): void
    {
        // noop, prevent Application from setting this value
    }

    /**
     * @param int $level
     */
    public function setVerbosity($level): void
    {
        // noop, prevent Application from setting this value
    }

    public function getDisplay(): string
    {
        \rewind($this->getStream());

        if (false === $contents = \stream_get_contents($this->getStream())) {
            throw new \RuntimeException('Failed to read stream.');
        }

        return $contents;
    }

    public function getErrorDisplay(): string
    {
        if (!$this->error instanceof StreamOutput) {
            return '';
        }

        \rewind($this->error->getStream());

        if (false === $contents = \stream_get_contents($this->error->getStream())) {
            throw new \RuntimeException('Failed to read stream.');
        }

        return $contents;
    }
}
