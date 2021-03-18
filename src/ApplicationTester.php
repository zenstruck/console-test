<?php

namespace Zenstruck\Console\Test;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Tester\TesterTrait;

/**
 * @internal
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ApplicationTester
{
    use TesterTrait;

    private Application $application;
    private InputInterface $input;

    public function __construct(Application $application, InputInterface $input)
    {
        $this->application = $application;
        $this->input = $input;
    }

    public function execute(): CommandResult
    {
        if ($this->inputs) {
            $this->input->setStream(self::createStream($this->inputs));
        }

        $this->initOutput([]);

        $statusCode = $this->application->run($this->input, $this->output);

        return new CommandResult($statusCode, $this->getDisplay());
    }
}
