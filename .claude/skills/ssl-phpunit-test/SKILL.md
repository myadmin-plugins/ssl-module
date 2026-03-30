---
name: ssl-phpunit-test
description: Creates a PHPUnit 9.6 test class in `tests/` for the myadmin-ssl-module. Use when user says 'add test', 'write unit test', 'test Plugin', or adds files to `tests/`. Covers class structure, static properties, hook configuration, method signatures, and source-level static analysis via ReflectionClass. Do NOT use for integration tests requiring live DB, MyAdmin framework globals, or live Symfony EventDispatcher dispatch.
---
# ssl-phpunit-test

## Critical

- Never import or instantiate MyAdmin core classes (`TFSmarty`, `\MyAdmin\Mail`, `get_module_db`) in tests — the bootstrap provides no framework stubs. Test via `ReflectionClass` and `file_get_contents` on the source file instead.
- `tests/bootstrap.php` defines `PRORATE_BILLING` (needed for `Plugin::$settings` static init). PHPUnit must be invoked with this bootstrap — it is already wired in `phpunit.xml.dist`.
- Test class namespace: `Detain\MyAdminSsl\Tests`. File must be named `*Test.php` and placed in `tests/` so `phpunit.xml.dist` auto-discovers it.
- Indentation: tabs only (`.scrutinizer.yml` enforces `use_tabs: true`).

## Instructions

1. **Verify bootstrap is intact.** Confirm `tests/bootstrap.php` defines `PRORATE_BILLING`. If missing, fix bootstrap before writing the test.

2. **Create the test file** at `tests/PluginTest.php`:

```php
<?php

namespace Detain\MyAdminSsl\Tests;

use PHPUnit\Framework\TestCase;
use Detain\MyAdminSsl\Plugin;
use ReflectionClass;

class PluginTest extends TestCase
{
	/** @var ReflectionClass<Plugin> */
	private ReflectionClass $reflection;

	protected function setUp(): void
	{
		$this->reflection = new ReflectionClass(Plugin::class);
	}
```

3. **Group tests with section comments** matching this order:
	- Class structure (`testClassExists`, `testClassNamespace`, `testClassIsInstantiable`, `testCanBeInstantiated`)
	- Static properties — assert value AND visibility: `isPublic()`, `isStatic()`
	- Settings array — assert `assertIsArray`, key presence via `assertArrayHasKey`, exact values via `assertSame`
	- `getHooks()` — assert count, key prefix `ssl.`, callback shape `[Plugin::class, 'methodName']`, method existence
	- Method signatures — assert `isPublic()`, `isStatic()`, parameter count, `GenericEvent` type hint via `$params[0]->getType()->getName()`
	- Source file inspection — use `file_get_contents($this->reflection->getFileName())` to assert strings

4. **Static property visibility pattern** (use for every static property):
```php
public function testNamePropertyIsPublicStatic(): void
{
	$prop = $this->reflection->getProperty('name');
	$this->assertTrue($prop->isPublic());
	$this->assertTrue($prop->isStatic());
}
```

5. **Hook callback shape pattern**:
```php
$hooks = Plugin::getHooks();
$this->assertArrayHasKey('ssl.load_processing', $hooks);
$this->assertSame([Plugin::class, 'loadProcessing'], $hooks['ssl.load_processing']);
```

6. **GenericEvent type-hint check pattern**:
```php
$params = $this->reflection->getMethod('loadProcessing')->getParameters();
$this->assertSame(
	'Symfony\\Component\\EventDispatcher\\GenericEvent',
	$params[0]->getType()->getName()
);
```

7. **Source inspection pattern** for verifying framework usage without running it:
```php
$source = file_get_contents($this->reflection->getFileName());
$this->assertStringContainsString('get_module_db(', $source);
$this->assertStringContainsString('adminMail(', $source);
```

8. **Run tests** to verify: `vendor/bin/phpunit` — all tests must pass with zero warnings (`failOnWarning="true"` in `phpunit.xml.dist`).

## Examples

User says: "Add a test to verify the ssl settings have the correct TABLE value"

Actions:
- Add to `tests/PluginTest.php` in the Settings section:
```php
public function testSettingsTable(): void
{
	$this->assertSame('ssl_certs', Plugin::$settings['TABLE']);
}
```
- Run `vendor/bin/phpunit` — confirm 1 new passing test.

User says: "Write a test for the getSettings method signature"

Actions:
- Add to the Method signatures section in `tests/PluginTest.php`:
```php
public function testGetSettingsIsPublicStatic(): void
{
	$method = $this->reflection->getMethod('getSettings');
	$this->assertTrue($method->isPublic());
	$this->assertTrue($method->isStatic());
}

public function testGetSettingsParameterTypeHint(): void
{
	$params = $this->reflection->getMethod('getSettings')->getParameters();
	$this->assertSame(
		'Symfony\\Component\\EventDispatcher\\GenericEvent',
		$params[0]->getType()->getName()
	);
}
```

## Common Issues

- **`PHP Fatal error: Uncaught Error: Undefined constant PRORATE_BILLING`**: `Plugin::$settings` uses this constant at class-load time. Fix: ensure `tests/bootstrap.php` defines it before autoload is required. Running `vendor/bin/phpunit` (not `php tests/PluginTest.php` directly) uses the correct bootstrap.
- **`Class 'Detain\MyAdminSsl\Plugin' not found`**: Run `composer install` first to generate the autoloader.
- **`failOnWarning` causes failure on deprecated ReflectionProperty access**: Use `$this->reflection->getProperty('name')` not `ReflectionProperty::setAccessible()` — it is not needed for public properties in PHP 8+.
- **Test auto-discovery fails**: Filename must end in `Test.php` and class must match filename. `phpunit.xml.dist` scans `tests/` with `suffix="Test.php"`.
- **`assertCount(16, Plugin::$settings)` fails after adding a setting**: Update both the count assertion in `testSettingsKeyCount()` and `testSettingsContainsAllExpectedKeys()` in `tests/PluginTest.php` when `Plugin::$settings` gains new keys.
