---
name: ssl-module-settings
description: Adds or modifies entries in Plugin::$settings and adds a corresponding UI control in getSettings() via add_dropdown_setting(). Use when user says 'add setting', 'new module config', 'out of stock toggle', 'enable/disable sales', or touches Plugin::$settings. Do NOT use for DB schema changes or adding new hook handlers.
---
# ssl-module-settings

## Critical

- `Plugin::$settings` keys MUST be UPPERCASE (e.g., `OUTOFSTOCK_SSL`). The `add_dropdown_setting()` call uses `get_setting('UPPERCASE_KEY')` — mismatch causes silent null.
- The `add_dropdown_setting()` snake_case field name (e.g., `outofstock_ssl`) must be the lowercase version of the settings key (`OUTOFSTOCK_SSL`). Core derives the key by uppercasing the field name.
- If adding a setting that references a PHP constant (like `PRORATE_BILLING`), define it in `tests/bootstrap.php` with a guard: `if (!defined('PRORATE_BILLING')) { define('PRORATE_BILLING', 1); }` — otherwise PHPUnit will fail at class load time.
- Do NOT modify `TABLE`, `PREFIX`, `TBLNAME`, `TITLE_FIELD` — these are structural constants consumed by MyAdmin core.
- Use tabs for indentation (`use_tabs: true` per `.scrutinizer.yml`).

## Instructions

1. **Add the entry to `Plugin::$settings`** in `src/Plugin.php`.
   - Append inside the `$settings` array (lines 19–35).
   - Key: UPPERCASE string. Value: scalar or defined constant.
   - Example: `'OUTOFSTOCK_SSL' => '0',`
   - Verify the key does not already exist before adding.

2. **Add a UI control in `getSettings()`** (line 105 area, `src/Plugin.php`).
   - Signature: `$settings->add_dropdown_setting(self::$module, _('General'), 'snake_key', _('Label'), _('Description'), $settings->get_setting('UPPER_KEY'), ['0', '1'], ['No', 'Yes']);`
   - The 3rd arg is the snake_case field name. The 6th arg calls `$settings->get_setting('UPPERCASE_KEY')`. Args 7 and 8 are parallel arrays of values and labels.
   - Wrap all display strings in `_(...)` for gettext i18n.
   - Verify the `get_setting()` argument exactly matches the `$settings` array key added in Step 1.

3. **Update the test for settings key count** in `tests/PluginTest.php`.
   - Find `testSettingsKeyCount()` (around line 406). Increment the `assertCount` value by 1 per new key.
   - Add the new key to the `$expectedKeys` array in `testSettingsContainsAllExpectedKeys()` (around line 416).
   - Add a dedicated test method `testSettings<KeyName>()` following the existing pattern (see `testSettingsImgname()` at line 280).

4. **Run tests** to confirm no regressions:
   ```bash
   vendor/bin/phpunit
   ```
   All tests must pass before committing.

5. **Before committing**, run:
   ```bash
   caliber refresh && git add CLAUDE.md .claude/ .cursor/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null
   ```

## Examples

**User says:** "Add an out-of-stock toggle setting for SSL"

**Actions taken:**

`src/Plugin.php` — add to `$settings`:
```php
public static $settings = [
    // ... existing keys ...
    'OUTOFSTOCK_SSL' => '0',
    'PREFIX' => 'ssl'];
```

`src/Plugin.php` — add to `getSettings()`:
```php
public static function getSettings(GenericEvent $event)
{
	/** @var \MyAdmin\Settings $settings **/
	$settings = $event->getSubject();
	$settings->add_dropdown_setting(self::$module, _('General'), 'outofstock_ssl', _('Out Of Stock Ssl'), _('Enable/Disable Sales Of This Type'), $settings->get_setting('OUTOFSTOCK_SSL'), ['0', '1'], ['No', 'Yes']);
}
```

`tests/PluginTest.php` — update count and add test:
```php
public function testSettingsKeyCount(): void
{
	$this->assertCount(17, Plugin::$settings); // was 16
}

public function testSettingsOutofstockSsl(): void
{
	$this->assertSame('0', Plugin::$settings['OUTOFSTOCK_SSL']);
}
```

**Result:** Setting appears in the admin UI under General, defaults to '0' (No), and all PHPUnit tests pass.

## Common Issues

- **`assertCount(16, ...)` failure after adding a key**: You added a key to `$settings` but forgot to update `testSettingsKeyCount()` in `tests/PluginTest.php`. Increment the count by 1.
- **`get_setting()` returns null in the UI**: The string passed to `get_setting()` doesn't match the `$settings` key. Both must be identical uppercase (e.g., `OUTOFSTOCK_SSL`).
- **`PHP Fatal error: Undefined constant 'MY_CONSTANT'`** during `vendor/bin/phpunit`: The constant is used in `$settings` static initialization but not defined before autoload. Add a guard to `tests/bootstrap.php`: `if (!defined('MY_CONSTANT')) { define('MY_CONSTANT', 0); }`
- **Indentation lint failure in `.scrutinizer.yml`**: File uses spaces instead of tabs. Editor auto-converted tabs — revert to tabs manually or run `unexpand --first-only -t 4 src/Plugin.php`.
