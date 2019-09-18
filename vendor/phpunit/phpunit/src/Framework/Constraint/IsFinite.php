{prologue}{class_declaration}
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = {configurable};
    private $__phpunit_returnValueGeneration = true;

{clone}{mocked_methods}
    public function expects(\PHPUnit\Framework\MockObject\Matcher\Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }
{method}
    public function __phpunit_setOriginalObject($originalObject)
    {
        $this->__phpunit_originalObject = $originalObject;
    }

    public function __phpunit_setReturnValueGeneration(bool $returnValueGeneration)
    {
        $this->__phpunit_returnValueGeneration = $returnValueGeneration;
    }

    public function __phpunit_getInvocationMocker()
    {
