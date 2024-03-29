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

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait InteractsWithConsole
{
    /**
     * @param string[] $inputs
     */
    final protected function executeConsoleCommand(string $command, array $inputs = []): CommandResult
    {
        return $this->consoleCommand($command)
            ->withInput($inputs)
            ->execute()
        ;
    }

    final protected function consoleCommand(string $command): TestCommand
    {
        if (!$this instanceof KernelTestCase) {
            throw new \LogicException(\sprintf('The %s trait can only be used with %s.', __TRAIT__, KernelTestCase::class));
        }

        if (!static::$booted) {
            static::bootKernel();
        }

        return TestCommand::from(new Application(self::$kernel), $command);
    }
}
