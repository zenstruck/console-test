# zenstruck/console-test

[![CI Status](https://github.com/zenstruck/console-test/workflows/CI/badge.svg)](https://github.com/zenstruck/console-test/actions?query=workflow%3ACI)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zenstruck/console-test/badges/quality-score.png?b=1.x)](https://scrutinizer-ci.com/g/zenstruck/console-test/?branch=1.x)
[![codecov](https://codecov.io/gh/zenstruck/console-test/branch/1.x/graph/badge.svg?token=KPQNKYGYRR)](https://codecov.io/gh/zenstruck/console-test)

Alternative, opinionated helper for testing Symfony console commands. This package is an alternative to
[`Symfony\Component\Console\Tester\CommandTester`](https://symfony.com/doc/current/console.html#testing-commands)
and helps make your tests more expressive and concise.

## Installation

```bash
composer require --dev zenstruck/console-test
```

## Symfony Framework Usage

You can run console commands in your tests by using the `InteractsWithConsole` trait in your
`KernelTestCase`/`WebTestCase` tests:

```php
use App\Command\CreateUserCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class CreateUserCommandTest extends KernelTestCase
{
    use InteractsWithConsole;

    public function test_can_create_user(): void
    {
        $this->executeConsoleCommand('create:user kbond --admin --role=ROLE_EMPLOYEE --role=ROLE_MANAGER')
            ->assertSuccessful() // command exit code is 0
            ->assertOutputContains('Creating admin user "kbond"')
            ->assertOutputContains('with roles: ROLE_EMPLOYEE, ROLE_MANAGER')
            ->assertOutputNotContains('regular user')
        ;

        // advanced usage
        $this->consoleCommand(CreateUserCommand::class) // can use the command class or "name"
            ->splitOutputStreams() // by default stdout/stderr are combined, this options splits them
            ->addArgument('kbond')
            ->addOption('--admin') // with or without "--" prefix
            ->addOption('role', ['ROLE_EMPLOYEE', 'ROLE_MANAGER'])
            ->addOption('-R') // shortcut options require the "-" prefix
            ->addOption('-vv') // by default, output has normal verbosity, use the standard options to change (-q, -v, -vv, -vvv)
            ->addOption('--ansi') // by default, output is undecorated, use this option to decorate
            ->execute() // run the command
            ->assertSuccessful()
            ->assertStatusCode(0) // equivalent to ->assertSuccessful()
            ->assertOutputContains('Creating admin user "kbond"')
            ->assertErrorOutputContains('this is in stderr') // used in conjunction with ->splitOutputStreams()
            ->assertErrorOutputNotContains('admin user') // used in conjunction with ->splitOutputStreams()
            ->dump() // dump() the status code/outputs and continue
            ->dd() // dd() the status code/outputs
        ;

        // testing interactive commands
        $this->executeConsoleCommand('create:user', ['kbond'])
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;

        // advanced testing interactive commands
        $this->consoleCommand(CreateUserCommand::class)
            ->addInput('kbond')
            ->addOption('--no-interaction') // commands are run interactively if input is provided, use this option to disable
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;

        // access result
        $result = $this->executeConsoleCommand('create:user');

        $result->statusCode();
        $result->output();
        $result->errorOutput();
    }
}
```

## Standalone Usage

You can test commands in unit tests or in a non-Symfony Framework context:

```php
use App\Command\CreateUserCommand;
use PHPUnit\Framework\TestCase;
use Zenstruck\Console\Test\TestCommand;

class CreateUserCommandTest extends TestCase
{
    public function test_can_create_user(): void
    {
        TestCommand::for(new CreateUserCommand(/** args... */))
            ->execute('kbond --admin --role=ROLE_EMPLOYEE --role=ROLE_MANAGER')
            ->assertSuccessful() // command exit code is 0
            ->assertOutputContains('Creating admin user "kbond"')
            ->assertOutputContains('with roles: ROLE_EMPLOYEE, ROLE_MANAGER')
            ->assertOutputNotContains('regular user')
        ;

        // advanced usage
        TestCommand::for(new CreateUserCommand(/** args... */))
            ->splitOutputStreams() // by default stdout/stderr are combined, this options splits them
            ->addArgument('kbond')
            ->addOption('--admin') // with or without "--" prefix
            ->addOption('role', ['ROLE_EMPLOYEE', 'ROLE_MANAGER'])
            ->addOption('-R') // shortcut options require the "-" prefix
            ->addOption('-vv') // by default, output has normal verbosity, use the standard options to change (-q, -v, -vv, -vvv)
            ->addOption('--ansi') // by default, output is undecorated, use this option to decorate
            ->execute()
            ->assertSuccessful()
            ->assertStatusCode(0) // equivalent to ->assertSuccessful()
            ->assertOutputContains('Creating admin user "kbond"')
            ->assertErrorOutputContains('this is in stderr') // used in conjunction with ->splitOutputStreams()
            ->assertErrorOutputNotContains('admin user') // used in conjunction with ->splitOutputStreams()
            ->dump() // dump() the status code/outputs and continue
            ->dd() // dd() the status code/outputs
        ;

        // testing interactive commands
        TestCommand::for(new CreateUserCommand(/** args... */))
            ->addInput('kbond')
            ->addOption('--no-interaction') // commands are run interactively if input is provided, use this option to disable
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;

        // access result
        $result = TestCommand::for(new CreateUserCommand(/** args... */))->execute();

        $result->statusCode();
        $result->output();
        $result->errorOutput();
    }
}
```

## Standardize Terminal Width

Under different terminal environments (ie Windows, Linux, Github Actions) the default
terminal width can be calculated differently. Since certain Symfony output helpers
use this to wrap long lines this can lead to output assertions failing in different
environments. It is recommended to standardize the terminal width by setting the
`COLUMNS` environment variable for your test suite:

```xml
<!-- phpunit.xml -->

<phpunit>
    <!-- ... -->
    <php>
        <env name="COLUMNS" value="120" />
    </php>
    <!-- ... -->
</phpunit>
```
