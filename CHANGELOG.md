# CHANGELOG

## [v1.7.0](https://github.com/zenstruck/console-test/releases/tag/v1.7.0)

January 8th, 2025 - [v1.6.1...v1.7.0](https://github.com/zenstruck/console-test/compare/v1.6.1...v1.7.0)

* 61189de feat: Add `assertOutputEmpty`/`NotEmpty` and `assertErrorOutputEmpty`/`NotEmpty` (#26) by @smnandre
* c7347a2 minor: Complete InteractsWithConsole test coverage (#25) by @smnandre
* 44b4af3 fix: unwrap `LazyCommand` (#27) by @kbond, Jérémy

## [v1.6.1](https://github.com/zenstruck/console-test/releases/tag/v1.6.1)

October 28th, 2024 - [v1.6.0...v1.6.1](https://github.com/zenstruck/console-test/compare/v1.6.0...v1.6.1)

* a90833d fix: Allow falsy values to be passed to addOption and to the command execution (#23) by @TristanPouliquen

## [v1.6.0](https://github.com/zenstruck/console-test/releases/tag/v1.6.0)

July 24th, 2024 - [v1.5.0...v1.6.0](https://github.com/zenstruck/console-test/compare/v1.5.0...v1.6.0)

* f727837 feat: add `assertFaulty` assertion (#21) by @raphaelstolt
* 0625e55 chore: keep releases lean (#20) by @raphaelstolt

## [v1.5.0](https://github.com/zenstruck/console-test/releases/tag/v1.5.0)

October 24th, 2023 - [v1.4.0...v1.5.0](https://github.com/zenstruck/console-test/compare/v1.4.0...v1.5.0)

* cd1366d feat: Symfony 7 support (#19) by @kbond
* d0ed4fe chore: run php-cs-fixer on php 8 by @kbond
* 5fe2744 feat: require php 8+, symfony 5.4+ (#18) by @kbond
* 2eb231b fix(ci): add token by @kbond
* 11fb61f chore(ci): fix by @kbond
* 6019627 chore: update ci config (#17) by @kbond
* e5c000b ci: fix (#16) by @kbond

## [v1.4.0](https://github.com/zenstruck/console-test/releases/tag/v1.4.0)

October 6th, 2022 - [v1.3.0...v1.4.0](https://github.com/zenstruck/console-test/compare/v1.3.0...v1.4.0)

* d259ce4 [feature] add ability to test command completion (#15) by @kbond
* 686fbd2 [minor] simplify sca gha (#14) by @kbond
* 5995408 [minor] support Symfony 6.1 (#11) by @kbond

## [v1.3.0](https://github.com/zenstruck/console-test/releases/tag/v1.3.0)

April 1st, 2022 - [v1.2.0...v1.3.0](https://github.com/zenstruck/console-test/compare/v1.2.0...v1.3.0)

* 991f765 [minor] remove scrutinizer (#10) by @kbond
* 6a26847 [feature] add `TestCommand::expectException()` (#10) by @kbond
* dad0fae [minor] add static code analysis with phpstan (#9) by @kbond
* ef51134 [minor] output cli when using dump/dd by @kbond

## [v1.2.0](https://github.com/zenstruck/console-test/releases/tag/v1.2.0)

October 22nd, 2021 - [v1.1.0...v1.2.0](https://github.com/zenstruck/console-test/compare/v1.1.0...v1.2.0)

* 19e0705 [feature] run commands via application (#8) by @kbond

## [v1.1.0](https://github.com/zenstruck/console-test/releases/tag/v1.1.0)

October 19th, 2021 - [v1.0.0...v1.1.0](https://github.com/zenstruck/console-test/compare/v1.0.0...v1.1.0)

* 12b200c [minor] refactor (#7) by @kbond
* 442d888 [feature] always use ConsoleOutput for tests (closes #6) (#7) by @kbond
* 48fb609 [feature] allow passing cli string to TestCommand::execute() (#5) by @kbond
* 7d6d6a0 [minor] add .editorconfig (#5) by @kbond
* aaa1597 [ci] use reusable workflows (#4) by @kbond

## [v1.0.0](https://github.com/zenstruck/console-test/releases/tag/v1.0.0)

September 27th, 2021 - _[Initial Release](https://github.com/zenstruck/console-test/commits/v1.0.0)_
