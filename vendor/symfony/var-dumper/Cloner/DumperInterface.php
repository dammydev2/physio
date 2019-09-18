<span class=sf-dump-str title="39 characters">hello(?stdClass $a, stdClass $b = null)</span></a>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStubWithNotExistingClass()
    {
        $var = [new ClassStub(NotExisting::class)];

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dump = $dumper->dump($cloner->cloneVar($var), true);

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="Symfony\Component\VarDumper\Tests\Caster\NotExisting
52 characters"><span class="sf-dump-ellipsis sf-dump-ellipsis-class">Symfony\Component\VarDumper\Tests\Caster</span><span class=sf-dump-ellipsis>\</span>NotExisting</span>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStubWithNotExistingMethod()
    {
        $var = [new ClassStub('hello', [FooInterface::class, 'missing'])];

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dump = $dumper->dump($cloner->cloneVar($var), true, ['fileLinkFormat' => '%f:%l']);

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<a href="%sFooInterface.php:5" rel="noopener noreferrer"><span class=sf-dump-str title="5 characters">hello</span></a>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStubWithAnonymousClass()
    {
        $var = [new ClassStub(\get_class(new class() extends \Exception {
        }))];

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader(