<?php

namespace Zenstruck\Console\Test\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\Console\Test\TestCommand;
use Zenstruck\Console\Test\Tests\Fixture\FixtureCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class UnitTest extends TestCase
{
    use ResetVerbosity;

    /**
     * @test
     */
    public function command_with_no_arguments(): void
    {
        TestCommand::for(new FixtureCommand())->execute()
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputNotContains('arg1')
            ->assertOutputNotContains('opt1')
        ;
    }

    /**
     * @test
     */
    public function can_add_input(): void
    {
        TestCommand::for(new FixtureCommand(), ['foobar'])->execute()
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputContains('arg1 value: foobar')
            ->assertOutputNotContains('opt1')
        ;
    }

    /**
     * @test
     */
    public function default_verbosity_and_decorated(): void
    {
        TestCommand::for(new FixtureCommand())
            ->execute()
            ->assertOutputContains('verbosity: 32')
            ->assertOutputContains('decorated: no')
        ;
    }

    /**
     * @test
     */
    public function can_decorate_with_ansi_option(): void
    {
        TestCommand::for(new FixtureCommand())
            ->addOption('--ansi')
            ->execute()
            ->assertOutputContains('decorated: yes')
        ;
    }

    /**
     * @test
     */
    public function can_adjust_verbosity_with_v_option(): void
    {
        TestCommand::for(new FixtureCommand())
            ->addOption('-vv')
            ->execute()
            ->assertOutputContains('verbosity: 128')
        ;
    }
}
