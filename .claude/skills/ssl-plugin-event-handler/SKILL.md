---
name: ssl-plugin-event-handler
description: Adds a new event handler to `src/Plugin.php` following the GenericEvent pattern used in the SSL module. Registers hook in `getHooks()`, implements handler as a public static method accepting `GenericEvent $event` and calling `$event->getSubject()`. Use when user says 'add hook', 'new event handler', 'register event', or modifies `Plugin::getHooks()`. Do NOT use for modifying existing handlers or for non-SSL modules.
---
# SSL Plugin Event Handler

## Critical

- All handler methods MUST be `public static` — never instance methods
- Parameter MUST be type-hinted as `GenericEvent` (the `use` import already exists at line 5 of `src/Plugin.php`)
- Hook keys MUST be prefixed with `self::$module.'.'` — never hardcode `'ssl.'`
- Hook values MUST be `[__CLASS__, 'methodName']` — never a closure or string
- Every handler method MUST have a `@param \Symfony\Component\EventDispatcher\GenericEvent $event` docblock
- The `testPublicMethodCount` and `testExpectedPublicMethods` tests in `tests/PluginTest.php` count exact method totals — update those tests when adding a handler

## Instructions

1. **Open `src/Plugin.php`** and add the new hook key → callback pair inside `getHooks()`. Use `self::$module` for the prefix:
   ```php
   public static function getHooks()
   {
       return [
           self::$module.'.load_processing' => [__CLASS__, 'loadProcessing'],
           self::$module.'.settings'        => [__CLASS__, 'getSettings'],
           self::$module.'.your_event'      => [__CLASS__, 'yourHandler'],  // add here
       ];
   }
   ```
   Verify: the key follows the pattern `modulename.event_name` (lowercase, dot-separated).

2. **Add the handler method** in `src/Plugin.php` after the last existing handler. Use this exact signature and boilerplate:
   ```php
   /**
    * @param \Symfony\Component\EventDispatcher\GenericEvent $event
    */
   public static function yourHandler(GenericEvent $event)
   {
       $subject = $event->getSubject();
       $settings = get_module_settings(self::$module);
       $db = get_module_db(self::$module);
       // handler logic here
   }
   ```
   - Use tabs for indentation (`.scrutinizer.yml` enforces `use_tabs: true`)
   - Wrap user-visible strings in `_('string')` for gettext
   - Escape all user input with `$db->real_escape()` before interpolation
   - Use `myadmin_log(self::$module, 'info', $message, __LINE__, __FILE__)` for logging

3. **If the handler needs DB writes**, use the established query pattern:
   ```php
   $db->query("update {$settings['TABLE']} set {$settings['PREFIX']}_status='active' where {$settings['PREFIX']}_id='{$id}'", __LINE__, __FILE__);
   $GLOBALS['tf']->history->add($settings['TABLE'], 'change_status', 'active', $id, $custid);
   ```
   Never use PDO. Never build INSERT strings manually — use `make_insert_query($table, $data)`.

4. **If the handler sends email**, follow the TFSmarty + MyAdmin\Mail pattern:
   ```php
   $smarty = new \TFSmarty();
   $smarty->assign('ssl_hostname', $serviceInfo[$settings['PREFIX'].'_hostname']);
   $email = $smarty->fetch('email/admin/ssl_your_event.tpl');
   $subject = 'SSL Event '.$serviceInfo[$settings['TITLE_FIELD']];
   (new \MyAdmin\Mail())->adminMail($subject, $email, false, 'admin/ssl_your_event.tpl');
   ```

5. **Update `tests/PluginTest.php`** — the test suite hard-counts public methods and lists expected method names:
   - `testPublicMethodCount`: increment the `assertCount` value by 1
   - `testExpectedPublicMethods`: add `'yourHandler'` to the `$expected` array
   - Add a `testGetHooksContainsYourEvent` test mirroring `testGetHooksContainsLoadProcessing`
   - Add a `testYourHandlerIsPublicStatic` and `testYourHandlerParameterTypeHint` test mirroring the existing `loadProcessing` tests

6. **Run the test suite** to confirm nothing broke:
   ```bash
   vendor/bin/phpunit
   ```
   All tests must pass before committing.

7. **Before committing**, run `caliber refresh` then stage doc files:
   ```bash
   caliber refresh && git add CLAUDE.md .claude/ .cursor/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null
   ```

## Examples

**User says:** "Add a hook for `ssl.queue_renewal` that logs a message when triggered."

**Actions:**

`getHooks()` addition in `src/Plugin.php`:
```php
self::$module.'.queue_renewal' => [__CLASS__, 'queueRenewal'],
```

New method in `src/Plugin.php`:
```php
/**
 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
 */
public static function queueRenewal(GenericEvent $event)
{
	$serviceInfo = $event->getSubject();
	$settings = get_module_settings(self::$module);
	myadmin_log(self::$module, 'info', 'Queuing renewal for '.$serviceInfo[$settings['TITLE_FIELD']], __LINE__, __FILE__);
}
```

`tests/PluginTest.php` update:
```php
// testPublicMethodCount: assertCount(5, ...) — was 4
// testExpectedPublicMethods: add 'queueRenewal'
```

**Result:** `vendor/bin/phpunit` passes; hook fires when `run_event('ssl.queue_renewal', $data, 'ssl')` is called from MyAdmin core.

## Common Issues

- **`testPublicMethodCount` fails with "Failed asserting that 5 matches expected 4"**: You added a method but forgot to update the `assertCount` in that test. Increment by 1 per new handler.
- **`testAllHookHandlersAcceptGenericEvent` fails**: Your handler parameter is missing the `GenericEvent` type hint. Signature must be `public static function handler(GenericEvent $event)`.
- **`testGetHooksKeysUseModulePrefix` fails**: You hardcoded `'ssl.event_name'` instead of `self::$module.'.event_name'`.
- **`testGetHooksCallbacksAreValidMethodReferences` fails with "references non-existent method"**: The method name string in `getHooks()` doesn't match the actual method name — check for typos.
- **`Class 'TFSmarty' not found` in tests**: TFSmarty is a MyAdmin core class stubbed in `tests/bootstrap.php`. Add a stub there if testing email-sending handlers in isolation.
