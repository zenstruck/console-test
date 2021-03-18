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
}
