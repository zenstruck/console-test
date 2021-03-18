<?php

namespace Zenstruck\Console\Test\Tests;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait ResetVerbosity
{
    /**
     * @before
     */
    public function resetVerbosity(): void
    {
        if (\function_exists('putenv')) {
            @\putenv('SHELL_VERBOSITY=0');
        }

        $_ENV['SHELL_VERBOSITY'] = 0;
        $_SERVER['SHELL_VERBOSITY'] = 0;
    }
}
