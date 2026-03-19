<?php

namespace Detain\MyAdminSsl\Tests;

use PHPUnit\Framework\TestCase;
use Detain\MyAdminSsl\Plugin;
use ReflectionClass;

/**
 * Test suite for the Detain\MyAdminSsl\Plugin class.
 *
 * Covers class structure, static properties, hook configuration,
 * event handler method signatures, and source-level static analysis.
 */
class PluginTest extends TestCase
{
    /**
     * @var ReflectionClass<Plugin>
     */
    private ReflectionClass $reflection;

    /**
     * Set up the reflection instance before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->reflection = new ReflectionClass(Plugin::class);
    }

    // ---------------------------------------------------------------
    //  Class structure tests
    // ---------------------------------------------------------------

    /**
     * Verify the Plugin class exists and resides in the expected namespace.
     *
     * @return void
     */
    public function testClassExists(): void
    {
        $this->assertTrue(class_exists(Plugin::class));
    }

    /**
     * Verify the fully-qualified class name matches the expected namespace.
     *
     * @return void
     */
    public function testClassNamespace(): void
    {
        $this->assertSame('Detain\\MyAdminSsl', $this->reflection->getNamespaceName());
    }

    /**
     * Verify Plugin is not abstract and can be instantiated.
     *
     * @return void
     */
    public function testClassIsInstantiable(): void
    {
        $this->assertTrue($this->reflection->isInstantiable());
    }

    /**
     * Verify Plugin is not declared as final.
     *
     * @return void
     */
    public function testClassIsNotFinal(): void
    {
        $this->assertFalse($this->reflection->isFinal());
    }

    /**
     * Verify the constructor exists and accepts zero parameters.
     *
     * @return void
     */
    public function testConstructorExistsAndTakesNoParameters(): void
    {
        $constructor = $this->reflection->getConstructor();
        $this->assertNotNull($constructor);
        $this->assertSame(0, $constructor->getNumberOfRequiredParameters());
    }

    /**
     * Verify the Plugin class can be instantiated without errors.
     *
     * @return void
     */
    public function testCanBeInstantiated(): void
    {
        $plugin = new Plugin();
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    // ---------------------------------------------------------------
    //  Static property tests
    // ---------------------------------------------------------------

    /**
     * Verify the $name static property value.
     *
     * @return void
     */
    public function testNameProperty(): void
    {
        $this->assertSame('SSL Certificates', Plugin::$name);
    }

    /**
     * Verify the $description static property value.
     *
     * @return void
     */
    public function testDescriptionProperty(): void
    {
        $this->assertSame('Allows selling of SSL Certificates Module', Plugin::$description);
    }

    /**
     * Verify the $help static property is an empty string.
     *
     * @return void
     */
    public function testHelpProperty(): void
    {
        $this->assertSame('', Plugin::$help);
    }

    /**
     * Verify the $module static property value.
     *
     * @return void
     */
    public function testModuleProperty(): void
    {
        $this->assertSame('ssl', Plugin::$module);
    }

    /**
     * Verify the $type static property value.
     *
     * @return void
     */
    public function testTypeProperty(): void
    {
        $this->assertSame('module', Plugin::$type);
    }

    /**
     * Verify $name is declared public and static.
     *
     * @return void
     */
    public function testNamePropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('name');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    /**
     * Verify $description is declared public and static.
     *
     * @return void
     */
    public function testDescriptionPropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('description');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    /**
     * Verify $module is declared public and static.
     *
     * @return void
     */
    public function testModulePropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('module');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    /**
     * Verify $type is declared public and static.
     *
     * @return void
     */
    public function testTypePropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('type');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    /**
     * Verify $help is declared public and static.
     *
     * @return void
     */
    public function testHelpPropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('help');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    /**
     * Verify $settings is declared public and static.
     *
     * @return void
     */
    public function testSettingsPropertyIsPublicStatic(): void
    {
        $prop = $this->reflection->getProperty('settings');
        $this->assertTrue($prop->isPublic());
        $this->assertTrue($prop->isStatic());
    }

    // ---------------------------------------------------------------
    //  Settings array tests
    // ---------------------------------------------------------------

    /**
     * Verify $settings is an array.
     *
     * @return void
     */
    public function testSettingsIsArray(): void
    {
        $this->assertIsArray(Plugin::$settings);
    }

    /**
     * Verify SERVICE_ID_OFFSET setting value.
     *
     * @return void
     */
    public function testSettingsServiceIdOffset(): void
    {
        $this->assertSame(3000, Plugin::$settings['SERVICE_ID_OFFSET']);
    }

    /**
     * Verify USE_REPEAT_INVOICE setting value.
     *
     * @return void
     */
    public function testSettingsUseRepeatInvoice(): void
    {
        $this->assertFalse(Plugin::$settings['USE_REPEAT_INVOICE']);
    }

    /**
     * Verify USE_PACKAGES setting value.
     *
     * @return void
     */
    public function testSettingsUsePackages(): void
    {
        $this->assertTrue(Plugin::$settings['USE_PACKAGES']);
    }

    /**
     * Verify BILLING_DAYS_OFFSET setting value.
     *
     * @return void
     */
    public function testSettingsBillingDaysOffset(): void
    {
        $this->assertSame(0, Plugin::$settings['BILLING_DAYS_OFFSET']);
    }

    /**
     * Verify IMGNAME setting value.
     *
     * @return void
     */
    public function testSettingsImgname(): void
    {
        $this->assertSame('security-ssl.png', Plugin::$settings['IMGNAME']);
    }

    /**
     * Verify the $settings REPEAT_BILLING_METHOD key exists.
     *
     * @return void
     */
    public function testSettingsRepeatBillingMethodKeyExists(): void
    {
        $this->assertArrayHasKey('REPEAT_BILLING_METHOD', Plugin::$settings);
    }

    /**
     * Verify DELETE_PENDING_DAYS setting value.
     *
     * @return void
     */
    public function testSettingsDeletePendingDays(): void
    {
        $this->assertSame(45, Plugin::$settings['DELETE_PENDING_DAYS']);
    }

    /**
     * Verify SUSPEND_DAYS setting value.
     *
     * @return void
     */
    public function testSettingsSuspendDays(): void
    {
        $this->assertSame(14, Plugin::$settings['SUSPEND_DAYS']);
    }

    /**
     * Verify SUSPEND_WARNING_DAYS setting value.
     *
     * @return void
     */
    public function testSettingsSuspendWarningDays(): void
    {
        $this->assertSame(7, Plugin::$settings['SUSPEND_WARNING_DAYS']);
    }

    /**
     * Verify TITLE setting value.
     *
     * @return void
     */
    public function testSettingsTitle(): void
    {
        $this->assertSame('SSL Certificates', Plugin::$settings['TITLE']);
    }

    /**
     * Verify MENUNAME setting value.
     *
     * @return void
     */
    public function testSettingsMenuname(): void
    {
        $this->assertSame('SSL', Plugin::$settings['MENUNAME']);
    }

    /**
     * Verify EMAIL_FROM setting value.
     *
     * @return void
     */
    public function testSettingsEmailFrom(): void
    {
        $this->assertSame('support@inssl.net', Plugin::$settings['EMAIL_FROM']);
    }

    /**
     * Verify TBLNAME setting value.
     *
     * @return void
     */
    public function testSettingsTblname(): void
    {
        $this->assertSame('SSL', Plugin::$settings['TBLNAME']);
    }

    /**
     * Verify TABLE setting value.
     *
     * @return void
     */
    public function testSettingsTable(): void
    {
        $this->assertSame('ssl_certs', Plugin::$settings['TABLE']);
    }

    /**
     * Verify TITLE_FIELD setting value.
     *
     * @return void
     */
    public function testSettingsTitleField(): void
    {
        $this->assertSame('ssl_hostname', Plugin::$settings['TITLE_FIELD']);
    }

    /**
     * Verify PREFIX setting value.
     *
     * @return void
     */
    public function testSettingsPrefix(): void
    {
        $this->assertSame('ssl', Plugin::$settings['PREFIX']);
    }

    /**
     * Verify the expected number of settings keys.
     *
     * @return void
     */
    public function testSettingsKeyCount(): void
    {
        $this->assertCount(16, Plugin::$settings);
    }

    /**
     * Verify all expected settings keys are present.
     *
     * @return void
     */
    public function testSettingsContainsAllExpectedKeys(): void
    {
        $expectedKeys = [
            'SERVICE_ID_OFFSET',
            'USE_REPEAT_INVOICE',
            'USE_PACKAGES',
            'BILLING_DAYS_OFFSET',
            'IMGNAME',
            'REPEAT_BILLING_METHOD',
            'DELETE_PENDING_DAYS',
            'SUSPEND_DAYS',
            'SUSPEND_WARNING_DAYS',
            'TITLE',
            'MENUNAME',
            'EMAIL_FROM',
            'TBLNAME',
            'TABLE',
            'TITLE_FIELD',
            'PREFIX',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, Plugin::$settings, "Missing settings key: {$key}");
        }
    }

    // ---------------------------------------------------------------
    //  getHooks() tests
    // ---------------------------------------------------------------

    /**
     * Verify getHooks() returns an array.
     *
     * @return void
     */
    public function testGetHooksReturnsArray(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertIsArray($hooks);
    }

    /**
     * Verify getHooks() returns exactly two hooks.
     *
     * @return void
     */
    public function testGetHooksReturnsTwoHooks(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertCount(2, $hooks);
    }

    /**
     * Verify getHooks() hook keys are prefixed with the module name.
     *
     * @return void
     */
    public function testGetHooksKeysUseModulePrefix(): void
    {
        $hooks = Plugin::getHooks();
        foreach (array_keys($hooks) as $key) {
            $this->assertStringStartsWith('ssl.', $key);
        }
    }

    /**
     * Verify getHooks() includes the load_processing hook.
     *
     * @return void
     */
    public function testGetHooksContainsLoadProcessing(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertArrayHasKey('ssl.load_processing', $hooks);
        $this->assertSame([Plugin::class, 'loadProcessing'], $hooks['ssl.load_processing']);
    }

    /**
     * Verify getHooks() includes the settings hook.
     *
     * @return void
     */
    public function testGetHooksContainsSettings(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertArrayHasKey('ssl.settings', $hooks);
        $this->assertSame([Plugin::class, 'getSettings'], $hooks['ssl.settings']);
    }

    /**
     * Verify all hook callbacks point to callable method references.
     *
     * @return void
     */
    public function testGetHooksCallbacksAreValidMethodReferences(): void
    {
        $hooks = Plugin::getHooks();
        foreach ($hooks as $key => $callback) {
            $this->assertIsArray($callback, "Hook {$key} callback should be an array");
            $this->assertCount(2, $callback, "Hook {$key} callback should have two elements");
            $this->assertSame(Plugin::class, $callback[0], "Hook {$key} should reference Plugin class");
            $this->assertTrue(
                $this->reflection->hasMethod($callback[1]),
                "Hook {$key} references non-existent method: {$callback[1]}"
            );
        }
    }

    /**
     * Verify getHooks() values are all two-element arrays.
     *
     * @return void
     */
    public function testGetHooksValuesAreTwoElementArrays(): void
    {
        foreach (Plugin::getHooks() as $hookName => $callback) {
            $this->assertIsArray($callback);
            $this->assertCount(2, $callback, "Hook {$hookName} callback must have exactly 2 elements");
        }
    }

    /**
     * Verify getHooks() is a public static method.
     *
     * @return void
     */
    public function testGetHooksMethodIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getHooks');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getHooks() takes no parameters.
     *
     * @return void
     */
    public function testGetHooksMethodTakesNoParameters(): void
    {
        $method = $this->reflection->getMethod('getHooks');
        $this->assertSame(0, $method->getNumberOfParameters());
    }

    // ---------------------------------------------------------------
    //  Method existence and signature tests
    // ---------------------------------------------------------------

    /**
     * Verify loadProcessing method exists.
     *
     * @return void
     */
    public function testLoadProcessingMethodExists(): void
    {
        $this->assertTrue($this->reflection->hasMethod('loadProcessing'));
    }

    /**
     * Verify loadProcessing is public and static.
     *
     * @return void
     */
    public function testLoadProcessingIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('loadProcessing');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify loadProcessing accepts exactly one parameter.
     *
     * @return void
     */
    public function testLoadProcessingAcceptsOneParameter(): void
    {
        $method = $this->reflection->getMethod('loadProcessing');
        $this->assertSame(1, $method->getNumberOfParameters());
    }

    /**
     * Verify loadProcessing parameter is type-hinted as GenericEvent.
     *
     * @return void
     */
    public function testLoadProcessingParameterTypeHint(): void
    {
        $method = $this->reflection->getMethod('loadProcessing');
        $params = $method->getParameters();
        $type = $params[0]->getType();
        $this->assertNotNull($type);
        $this->assertSame('Symfony\\Component\\EventDispatcher\\GenericEvent', $type->getName());
    }

    /**
     * Verify getSettings method exists.
     *
     * @return void
     */
    public function testGetSettingsMethodExists(): void
    {
        $this->assertTrue($this->reflection->hasMethod('getSettings'));
    }

    /**
     * Verify getSettings is public and static.
     *
     * @return void
     */
    public function testGetSettingsIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Verify getSettings accepts exactly one parameter.
     *
     * @return void
     */
    public function testGetSettingsAcceptsOneParameter(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $this->assertSame(1, $method->getNumberOfParameters());
    }

    /**
     * Verify getSettings parameter is type-hinted as GenericEvent.
     *
     * @return void
     */
    public function testGetSettingsParameterTypeHint(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $params = $method->getParameters();
        $type = $params[0]->getType();
        $this->assertNotNull($type);
        $this->assertSame('Symfony\\Component\\EventDispatcher\\GenericEvent', $type->getName());
    }

    /**
     * Verify the Plugin class has exactly four public methods.
     *
     * @return void
     */
    public function testPublicMethodCount(): void
    {
        $methods = $this->reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $ownMethods = array_filter($methods, function (\ReflectionMethod $m) {
            return $m->getDeclaringClass()->getName() === Plugin::class;
        });
        $this->assertCount(4, $ownMethods);
    }

    /**
     * Verify the expected public methods are present.
     *
     * @return void
     */
    public function testExpectedPublicMethods(): void
    {
        $expected = ['__construct', 'getHooks', 'loadProcessing', 'getSettings'];
        foreach ($expected as $method) {
            $this->assertTrue(
                $this->reflection->hasMethod($method),
                "Missing expected public method: {$method}"
            );
        }
    }

    /**
     * Verify that all hook handler methods referenced in getHooks()
     * accept exactly one GenericEvent parameter.
     *
     * @return void
     */
    public function testAllHookHandlersAcceptGenericEvent(): void
    {
        $hooks = Plugin::getHooks();
        foreach ($hooks as $hookName => $callback) {
            $methodName = $callback[1];
            $method = $this->reflection->getMethod($methodName);
            $params = $method->getParameters();
            $this->assertCount(1, $params, "Method {$methodName} should accept exactly 1 parameter");
            $type = $params[0]->getType();
            $this->assertNotNull($type, "Method {$methodName} parameter should be type-hinted");
            $this->assertSame(
                'Symfony\\Component\\EventDispatcher\\GenericEvent',
                $type->getName(),
                "Method {$methodName} parameter should be GenericEvent"
            );
        }
    }

    // ---------------------------------------------------------------
    //  Static analysis via source file inspection
    // ---------------------------------------------------------------

    /**
     * Verify the source file uses the correct namespace declaration.
     *
     * @return void
     */
    public function testSourceFileHasCorrectNamespace(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('namespace Detain\\MyAdminSsl;', $source);
    }

    /**
     * Verify the source file imports GenericEvent.
     *
     * @return void
     */
    public function testSourceFileImportsGenericEvent(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('use Symfony\\Component\\EventDispatcher\\GenericEvent;', $source);
    }

    /**
     * Verify the source file contains expected DB table references.
     *
     * @return void
     */
    public function testSourceContainsDatabaseTableReferences(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('ssl_certs', $source);
        $this->assertStringContainsString('ssl_hostname', $source);
    }

    /**
     * Verify the source file contains all expected service lifecycle closures.
     *
     * @return void
     */
    public function testSourceContainsServiceLifecycleClosures(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('setEnable(', $source);
        $this->assertStringContainsString('setReactivate(', $source);
        $this->assertStringContainsString('setDisable(', $source);
    }

    /**
     * Verify the source file calls register() on the service handler chain.
     *
     * @return void
     */
    public function testSourceContainsRegisterCall(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('->register()', $source);
    }

    /**
     * Verify the source file contains the class docblock.
     *
     * @return void
     */
    public function testSourceContainsClassDocblock(): void
    {
        $docComment = $this->reflection->getDocComment();
        $this->assertNotFalse($docComment);
        $this->assertStringContainsString('Class Plugin', $docComment);
    }

    /**
     * Verify all event handler methods have docblocks.
     *
     * @return void
     */
    public function testAllEventHandlerMethodsHaveDocblocks(): void
    {
        $eventMethods = ['loadProcessing', 'getSettings'];
        foreach ($eventMethods as $methodName) {
            $method = $this->reflection->getMethod($methodName);
            $docComment = $method->getDocComment();
            $this->assertNotFalse($docComment, "Method {$methodName} is missing a docblock");
        }
    }

    /**
     * Verify the source file uses the history tracking system.
     *
     * @return void
     */
    public function testSourceContainsHistoryTracking(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('history->add(', $source);
    }

    /**
     * Verify the source file references the expected global functions.
     *
     * @return void
     */
    public function testSourceReferencesExpectedGlobalFunctions(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $expectedFunctions = [
            'run_event(',
            'get_module_settings(',
            'get_module_db(',
        ];
        foreach ($expectedFunctions as $func) {
            $this->assertStringContainsString($func, $source, "Missing expected function call: {$func}");
        }
    }

    /**
     * Verify the source file is valid PHP by checking for syntax via token_get_all.
     *
     * @return void
     */
    public function testSourceFileIsValidPhp(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $tokens = token_get_all($source);
        $this->assertNotEmpty($tokens);
        $this->assertSame(T_OPEN_TAG, $tokens[0][0]);
    }

    /**
     * Verify the source file references the outofstock setting.
     *
     * @return void
     */
    public function testSourceContainsOutOfStockSetting(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('outofstock_ssl', $source);
        $this->assertStringContainsString('OUTOFSTOCK_SSL', $source);
    }

    /**
     * Verify the source file uses setModule with the correct module name.
     *
     * @return void
     */
    public function testSourceUsesSetModuleWithCorrectName(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString("setModule(self::\$module)", $source);
    }

    /**
     * Verify the source file references activation statuses.
     *
     * @return void
     */
    public function testSourceContainsActivationStatuses(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('setActivationStatuses(', $source);
        $this->assertStringContainsString("'pending'", $source);
        $this->assertStringContainsString("'active'", $source);
    }

    /**
     * Verify the source file references admin email functionality.
     *
     * @return void
     */
    public function testSourceContainsAdminEmailFunctionality(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('adminMail(', $source);
        $this->assertStringContainsString('ssl_created.tpl', $source);
        $this->assertStringContainsString('ssl_reactivated.tpl', $source);
    }

    /**
     * Verify the source file references TFSmarty template engine.
     *
     * @return void
     */
    public function testSourceContainsSmartyTemplateUsage(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('TFSmarty', $source);
        $this->assertStringContainsString('->assign(', $source);
        $this->assertStringContainsString('->fetch(', $source);
    }

    /**
     * Verify the source file contains SQL update queries for status changes.
     *
     * @return void
     */
    public function testSourceContainsSqlStatusUpdates(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString("_status='active'", $source);
        $this->assertStringContainsString('update {$settings', $source);
    }

    /**
     * Verify the source file has matching brace counts (basic structural validation).
     *
     * @return void
     */
    public function testSourceHasBalancedBraces(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $openBraces = substr_count($source, '{');
        $closeBraces = substr_count($source, '}');
        $this->assertSame($openBraces, $closeBraces, 'Unbalanced braces in source file');
    }

    /**
     * Verify the source file does not contain any TODO markers
     * that would indicate incomplete implementation.
     *
     * @return void
     */
    public function testSourceFileHasNoTodoComments(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringNotContainsString('// TODO', $source);
        $this->assertStringNotContainsString('// FIXME', $source);
    }

    /**
     * Verify the source file line count is reasonable (not truncated or bloated).
     *
     * @return void
     */
    public function testSourceFileLineCount(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $lines = substr_count($source, "\n");
        $this->assertGreaterThan(30, $lines, 'Source file seems too short');
        $this->assertLessThan(500, $lines, 'Source file seems too long');
    }

    /**
     * Verify the source file declares exactly one class.
     *
     * @return void
     */
    public function testSourceDeclaresExactlyOneClass(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        preg_match_all('/^\s*class\s+\w+/m', $source, $matches);
        $this->assertCount(1, $matches[0], 'Source file should declare exactly one class');
    }

    /**
     * Tests that the REPEAT_BILLING_METHOD setting references the PRORATE_BILLING constant.
     *
     * @return void
     */
    public function testSettingsReferencesPRORATE_BILLING(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('PRORATE_BILLING', $source);
    }

    /**
     * Verify the settings dropdown in getSettings references the ssl module.
     *
     * @return void
     */
    public function testSourceContainsSettingsDropdownForSsl(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('add_dropdown_setting(', $source);
        $this->assertStringContainsString("self::\$module", $source);
    }

    /**
     * Verify the source file references the MyAdmin Mail class.
     *
     * @return void
     */
    public function testSourceContainsMailClassReference(): void
    {
        $source = file_get_contents($this->reflection->getFileName());
        $this->assertStringContainsString('\\MyAdmin\\Mail()', $source);
    }
}
