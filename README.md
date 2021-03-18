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
            ->addArgument('kbond')
            ->addOption('--admin') // with or without "--" prefix
            ->addOption('role', ['ROLE_EMPLOYEE', 'ROLE_MANAGER'])
            ->catchExceptions() // by default, exceptions are thrown
            ->execute() // run the command
            ->assertSuccessful()
            ->assertStatusCode(0) // equivalent to ->assertSuccessful()
            ->assertOutputContains('Creating admin user "kbond"')
            ->dump() // dump() the status code/output and continue
            ->dd() // dd() the status code/output
        ;

        // testing interactive commands
        $this->executeConsoleCommand('create:user', ['kbond'])
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;
        
        // advanced testing interactive commands
        $this->consoleCommand(CreateUserCommand::class)
            ->addInput('kbond')
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;
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
            ->addArgument('kbond')
            ->addOption('--admin') // with or without "--" prefix
            ->addOption('role', ['ROLE_EMPLOYEE', 'ROLE_MANAGER'])
            ->catchExceptions() // by default, exceptions are thrown
            ->execute()
            ->assertSuccessful()
            ->assertStatusCode(0) // equivalent to ->assertSuccessful()
            ->assertOutputContains('Creating admin user "kbond"')
            ->dump() // dump() the status code/output and continue
            ->dd() // dd() the status code/output
        ;
        
        // testing interactive commands
        TestCommand::for(new CreateUserCommand(/** args... */))
            ->addInput('kbond')
            ->execute()
            ->assertSuccessful()
            ->assertOutputContains('Creating regular user "kbond"')
        ;
    }
}
```
