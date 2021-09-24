<?php

namespace Zenstruck\Console\Test\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Zenstruck\Console\Test\InteractsWithConsole;
use Zenstruck\Console\Test\Tests\Fixture\FixtureCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FunctionalTest extends KernelTestCase
{
    use InteractsWithConsole;

    /**
     * @test
     */
    public function string_command_with_no_arguments(): void
    {
        $this->executeConsoleCommand('fixture:command')
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputNotContains('arg1')
            ->assertOutputNotContains('opt1')
            ->assertOutputContains('Error output')
        ;
    }

    /**
     * @test
     */
    public function can_split_output_streams(): void
    {
        $this->consoleCommand('fixture:command')
            ->splitOutputStreams()
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputNotContains('arg1')
            ->assertOutputNotContains('opt1')
            ->assertOutputNotContains('Error output')
            ->assertErrorOutputContains('Error output')
            ->assertErrorOutputNotContains('Executing command')
        ;
    }

    /**
     * @test
     */
    public function class_name_command_with_no_arguments(): void
    {
        $this->executeConsoleCommand(FixtureCommand::class)
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputNotContains('arg1')
            ->assertOutputNotContains('opt1')
        ;
    }

    /**
     * @test
     */
    public function executing_unregistered_command(): void
    {
        $this->expectException(CommandNotFoundException::class);

        $this->executeConsoleCommand('not:registered');
    }

    /**
     * @test
     */
    public function command_with_string_arguments_and_options(): void
    {
        $this->executeConsoleCommand('fixture:command value --opt1 --opt2=v1 --opt3=v2 --opt3=v3')
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputContains('arg1 value: value')
            ->assertOutputContains('opt1 option set')
            ->assertOutputContains('opt2 value: v1')
            ->assertOutputContains('opt3 value: v2')
            ->assertOutputContains('opt3 value: v3')
        ;
    }

    /**
     * @test
     */
    public function command_with_builder_arguments_and_options(): void
    {
        $this->consoleCommand('fixture:command')
            ->addArgument('value')
            ->addOption('opt1')
            ->addOption('--opt2', 'v1')
            ->addOption('--opt3', ['v2', 'v3'])
            ->addOption('--opt3', 'v4')
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Executing command')
            ->assertOutputContains('arg1 value: value')
            ->assertOutputContains('opt1 option set')
            ->assertOutputContains('opt2 value: v1')
            ->assertOutputContains('opt3 value: v2')
            ->assertOutputContains('opt3 value: v3')
            ->assertOutputContains('opt3 value: v4')
        ;
    }

    /**
     * @test
     */
    public function exceptions_from_commands_are_thrown(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Exception thrown!');

        $this->consoleCommand('fixture:command --throw')->execute();
    }

    /**
     * @test
     */
    public function can_add_inputs(): void
    {
        $this->executeConsoleCommand('fixture:command', ['foobar'])
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
        $this->executeConsoleCommand('fixture:command')
            ->assertOutputContains('verbosity: 32')
            ->assertOutputContains('decorated: no')
        ;
    }

    /**
     * @test
     */
    public function can_decorate_with_ansi_option(): void
    {
        $this->executeConsoleCommand('fixture:command --ansi')
            ->assertOutputContains('decorated: yes')
        ;
    }

    /**
     * @test
     */
    public function can_adjust_verbosity_with_v_option(): void
    {
        $this->executeConsoleCommand('fixture:command -vv')
            ->assertOutputContains('verbosity: 128')
        ;
    }

    /**
     * @test
     */
    public function can_turn_off_interaction(): void
    {
        $this->executeConsoleCommand('fixture:command -n', ['kbond'])
            ->assertOutputNotContains('arg1')
            ->assertOutputNotContains('kbond')
        ;
    }

    /**
     * @test
     */
    public function terminal_width_is_standardized(): void
    {
        $this->executeConsoleCommand('fixture:command')
            ->assertOutputContains(' [OK] Long link:                                                                                                        ')
            ->assertOutputContains('      https://github.com/zenstruck/console-test/blob/997ee1f66743342ffd9cd00a77613ebfa2efd2b8/src/CommandResult.php     ')
        ;
    }
}
