# MyAdmin SSL Module

Composer plugin providing SSL certificate lifecycle management for [MyAdmin](https://github.com/detain/myadmin).

## Commands

```bash
composer install              # install deps including phpunit/phpunit ^9.6
vendor/bin/phpunit            # run all tests (uses tests/bootstrap.php)
```

## Architecture

- **Plugin class**: `src/Plugin.php` · namespace `Detain\MyAdminSsl\` · PSR-4 autoload
- **Tests**: `tests/PluginTest.php` · bootstrap `tests/bootstrap.php` · config `phpunit.xml.dist`
- **Event system**: `Symfony\Component\EventDispatcher\GenericEvent` · hooks registered in `Plugin::getHooks()`
- **Hook dispatch**: `run_event('event_name', $data, self::$module)` in MyAdmin core
- **CI/CD**: `.github/` · contains GitHub Actions workflows for automated testing and deployment
- **IDE Config**: `.idea/` · contains inspectionProfiles, deployment.xml, encodings.xml

## Module Settings (`Plugin::$settings`)

Key constants used across MyAdmin core:
- `TABLE` → DB table name (`ssl_certs`)
- `PREFIX` → column prefix (`ssl`)
- `TBLNAME` → display label (`SSL`)
- `TITLE_FIELD` → display column (`ssl_hostname`)
- `SERVICE_ID_OFFSET`, `SUSPEND_DAYS`, `DELETE_PENDING_DAYS`, `BILLING_DAYS_OFFSET`

## Plugin Hook Pattern

```php
// In Plugin::getHooks():
return [
    self::$module.'.load_processing' => [__CLASS__, 'loadProcessing'],
    self::$module.'.settings'        => [__CLASS__, 'getSettings'],
];

// Handler signature:
public static function loadProcessing(GenericEvent $event) {
    $service = $event->getSubject();
    $service->setModule(self::$module)
        ->setActivationStatuses(['pending', 'pendapproval', 'active'])
        ->setEnable(function ($service) { /* ... */ })
        ->setReactivate(function ($service) { /* ... */ })
        ->setDisable(function () { })
        ->register();
}
```

## DB + Email Pattern

```php
$settings = get_module_settings(self::$module);
$db = get_module_db(self::$module);
$db->query("update {$settings['TABLE']} set {$settings['PREFIX']}_status='active' where {$settings['PREFIX']}_id='{$id}'", __LINE__, __FILE__);

$smarty = new \TFSmarty();
$smarty->assign('ssl_hostname', $serviceInfo[$settings['PREFIX'].'_hostname']);
$email = $smarty->fetch('email/admin/ssl_created.tpl');
(new \MyAdmin\Mail())->adminMail($subject, $email, false, 'admin/ssl_created.tpl');
```

## Settings UI Pattern

```php
public static function getSettings(GenericEvent $event) {
    $settings = $event->getSubject();
    $settings->add_dropdown_setting(
        self::$module, _('General'),
        'outofstock_ssl', _('Out Of Stock Ssl'),
        _('Enable/Disable Sales Of This Type'),
        $settings->get_setting('OUTOFSTOCK_SSL'),
        ['0', '1'], ['No', 'Yes']
    );
}
```

## Conventions

- Tabs for indentation (see `.scrutinizer.yml` `use_tabs: true`)
- Constants uppercase: `PRORATE_BILLING` defined in `tests/bootstrap.php` if missing
- i18n: wrap strings in `_('string')` for gettext
- Commit messages: lowercase descriptive (`fix ssl activation`, `add reactivation hook`)
- Run `caliber refresh` before commits; stage modified doc files afterward

<!-- caliber:managed:pre-commit -->
## Plugin contract harness

This package is on the shared contract harness from `detain/myadmin-plugin-installer`.
`tests/ContractTest.php` is **generated** — run `composer myadmin:scaffold-tests` (add
`--force --write` to re-emit it), never hand-edit it.

The harness **executes** the plugin: it defines the bare constants the class body references
and then calls `getHooks()`, `getSettings()`, `getMenu()`, `apiRegister()` and — for
`type=service` packages — the activate/deactivate/change-ip/queue handlers, for real.

**So do not write reflection-only tests for the plugin class.** Asserting a handler exists,
is public, is static and takes one parameter passes whether or not the handler works; three
production bugs in this fleet were sitting behind assertions of exactly that shape. Older
guidance in this repo that says those methods must not be called predates the harness.

The harness is **additive**: it runs alongside this package's existing tests, and nothing is
deleted to make room for it. Run the whole suite, never `--filter ContractTest` alone — the
contract class primes constants and calls `register_module()`, neither of which can be undone.

See the `plugin-contract-tests` skill for the full workflow, and `docs/testing-harness.md` in
the installer.

## Before Committing

**IMPORTANT:** Before every git commit, you MUST ensure Caliber syncs agent configs with the latest code changes.

First, check if the pre-commit hook is already installed:
```bash
grep -q "caliber" .git/hooks/pre-commit 2>/dev/null && echo "hook-active" || echo "no-hook"
```

- If **hook-active**: the hook handles sync automatically — just commit normally. Tell the user: "Caliber will sync your agent configs automatically via the pre-commit hook."
- If **no-hook**: run Caliber manually before committing:
  1. Tell the user: "Caliber: Syncing agent configs with your latest changes..."
  2. Run: `caliber refresh && git add CLAUDE.md .claude/ .cursor/ .cursorrules .github/copilot-instructions.md .github/instructions/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null`
  3. After it completes, briefly tell the user what Caliber updated. Then proceed with the commit.

If `caliber` is not found, tell the user: "This project uses Caliber for agent config sync. Run /setup-caliber to get set up."
<!-- /caliber:managed:pre-commit -->

<!-- caliber:managed:learnings -->
## Session Learnings

Read `CALIBER_LEARNINGS.md` for patterns and anti-patterns learned from previous sessions.
These are auto-extracted from real tool usage — treat them as project-specific rules.
<!-- /caliber:managed:learnings -->
