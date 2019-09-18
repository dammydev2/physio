d final. It may change without further notice as of its next major version. You should not extend it from "Test\Symfony\Component\Debug\Tests\ExtendsFinalClass8".',
        ], $deprecations);
    }

    public function testExtendedFinalMethod()
    {
        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) { $deprecations[] = $msg; });
        $e = error_reporting(E_USER_DEPRECATED);

        class_exists(__NAMESPACE__.'\\Fixtures\\ExtendedFinalMethod', true);

        error_reporting($e);
        restore_error_handler();

        $xError = [
            'The "Symfony\Component\Debug\Tests\Fixtures\FinalMethod::finalMethod()" method is considered final. It may change without further notice as of its next major version. You should not extend it from "Symfony\Component\Debug\Tests\Fixtures\ExtendedFinalMethod".',
            'The "Symfony\Component\Debug\Tests\Fixtures\FinalMethod::finalMethod2()" method is considered final. It may change without further notice as of its next major version. You should not extend it from "Symfony\Component\Debug\Tests\Fixtures\ExtendedFinalMethod".',
        ];

        $this->assertSame($xError, $deprecations);
    }

    public function testExtendedDeprecatedMethodDoesntTriggerAnyNotice()
    {
        set_error_handler(function () { return false; });
        $e = error_reporting(0);
        trigger_error('', E_USER_NOTICE);

        class_exists('Test\\'.__NAMESPACE__.'\\ExtendsAnnotatedClass', true);

        error_reporting($e);
        restore_error_handler();

        $lastError = error_get_last();
        unset($lastError['file'], $lastError['line']);

        $this->assertSame(['type' => E_USER_NOTICE, 'message' => ''], $lastError);
    }

    public function testInternalsUse()
    {
        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) { $deprecations[] = $msg; });
        $e = error_reporting(E_USER_DEPRECATED);

        class_exists('Test\\'.__NAMESPACE__.'\\ExtendsInternals', true);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame($deprecations, [
            'The "Symfony\Component\Debug\Tests\Fixtures\InternalInterface" interface is considered internal. It may change without further notice. You should not use it from "Test\Symfony\Component\Debug\Tests\ExtendsInternalsParent".',
            'The "Symfony\Component\Debug\Tests\Fixtures\InternalClass" class is considered internal. It may change without further notice. You should not use it from "Test\Symfony\Component\Debug\Tests\ExtendsInternalsParent".',
            'The "Symfony\Component\Debug\Tests\Fixtures\InternalTrait" trait is considered internal. It may change without further notice. You should not use it from "Test\Symfony\Component\Debug\Tests\ExtendsInternals".',
            'The "Symfony\Component\Debug\Tests\Fixtures\InternalClass::internalMethod()" method is considered internal. It may change without further notice. You should not extend it from "Test\Symfony\Component\Debug\Tests\ExtendsInternals".',
        ]);
    }

    public function testExtendedMethodDefinesNewParameters()
    {
        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) { $deprecations[] = $msg; });
        $e = error_reporting(E_USER_DEPRECATED);

        class_exists(__NAMESPACE__.'\\Fixtures\SubClassWithAnnotatedParameters', true);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([
            'The "Symfony\Component\Debug\Tests\Fixtures\SubClassWithAnnotatedParameters::quzMethod()" method will require a new "Quz $quz" argument in the next major version of its parent class "Symfony\Component\Debug\Tests\Fixtures\ClassWithAnnotatedParameters", not defining it is deprecated.',
            'The "Symfony\Component\Debug\Tests\Fixtures\SubClassWithAnnotatedParameters::whereAmI()" method will require a new "bool $matrix" argument in the next major version of its parent class "Symfony\Component\Debug\Tests\Fixtures\InterfaceWithAnnotatedParameters", not defining it is deprecated.',
            'The "Symfony\Component\Debug\Tests\Fixtures\SubClassWithAnnotatedParameters::isSymfony()" method will require a new "true $yes" argument in the next major version of its parent class "Symfony\Component\Debug\Tests\Fixtures\ClassWithAnnotatedParameters", not defining it is deprecated.',
        ], $deprecations);
    }

    public function testUseTraitWithInternalMethod()
    {
        $deprecations = [];
        set_error_handler(function ($type, $msg) use (&$deprecations) { $deprecations[] = $msg; });
        $e = error_reporting(E_USER_DEPRECATED);

        class_exists('Test\\'.__NAMESPACE__.'\\UseTraitWithInternalMethod', true);

        error_reporting($e);
        restore_error_handler();

        $this->assertSame([], $deprecations);
    }

    public function testEvaluatedCode()
    {
        $this->assertTrue(class_exists(__NAMESPACE__.'\Fixtures\DefinitionInEvaluatedCode', true));
    }
}

class ClassLoader
{
    public function loadClass($class)
    {
    }

    public function getClassMap()
    {
        return [__NAMESPACE__.'\Fixtures\NotPSR0bis' => __DIR__.'/Fixtures/notPsr0Bis.php'];
    }

    public function findFile($class)
    {
        $fixtureDir = __DIR__.\DIRECTORY_SEPARATOR.'Fixtures'.\DIRECTORY_SEPARATOR;

        if (__NAMESPACE__.'\TestingUnsilencing' === $class) {
            eval('-- parse error --');
        } elseif (__NAMESPACE__.'\TestingStacking' === $class) {
            eval('namespace '.__NAMESPACE__.'; class TestingStacking { function foo() {} }');
        } elseif (__NAMESPACE__.'\TestingCaseMismatch' === $class) {
            eval('namespace '.__NAMESPACE__.'; class TestingCaseMisMatch {}');
        } elseif (__NAMESPACE__.'\Fixtures\Psr4CaseMismatch' === $class) {
            return $fixtureDir.'psr4'.\DIRECTORY_SEPARATOR.'Psr4CaseMismatch.php';
        } elseif (__NAMESPACE__.'\Fixtures\NotPSR0' === $class) {
            return $fixtureDir.'reallyNotPsr0.php';
        } elseif (__NAMESPACE__.'\Fixtures\NotPSR0bis' === $class) {
            return $fixtureDir.'notPsr0Bis.php';
        } elseif ('Symfony\Bridge\Debug\Tests\Fixtures\ExtendsDeprecatedParent' === $class) {
            eval('namespace Symfony\Bridge\Debug\Tests\Fixtures; class ExtendsDeprecatedParent extends \\'.__NAMESPACE__.'\Fixtures\DeprecatedClass {}');
        } elseif ('Test\\'.__NAMESPACE__.'\DeprecatedParentClass' === $class) {
            eval('namespace Test\\'.__NAMESPACE__.'; class DeprecatedParentClass extends \\'.__NAMESPACE__.'\Fixtures\DeprecatedClass {}');
        } elseif ('Test\\'.__NAMESPACE__.'\DeprecatedInterfaceClass' === $class) {
            eval('namespace Test\\'.__NAMESPACE__.'; class DeprecatedInterfaceClass implements \\'.__NAMESPACE__.'\Fixtures\DeprecatedInterface {}');
        } elseif ('Test\\'.__NAMESPACE__.'\NonDeprecatedInterfaceClass' === $class) {
            eval('namespace Test\\'.__NAMESPACE__.'; class NonDeprecatedInterfaceClass implements \\'.__NAMESPACE__.'\Fixtures\NonDeprecatedInterface {}');
        } elseif ('Test\\'.__NAMESPACE__.'\Float' === $class) {
            eval('namespace Tes