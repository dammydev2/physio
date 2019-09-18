ts \NS\C, \NS\D
{
    use \NS\E, \NS\F, \NS\G {
        f as private g;
        \NS\E::h as i;
        \NS\E::j insteadof \NS\F, \NS\G;
    }
}
interface A extends \NS\C, \NS\D
{
    public function a(\NS\A $a) : \NS\A;
}
function fn(\NS\A $a) : \NS\A
{
}
function fn2(array $a) : array
{
}
function (\NS\A $a) : \NS\A {
};
function fn3(?\NS\A $a) : ?\NS\A
{
}
function fn4(?array $a) : ?array
{
}
\NS\A::b();
\NS\A::$b;
\NS\A::B;
new \NS\A();
$a instanceof \NS\A;
\NS\a();
\NS\A;
try {
    $someThing;
} catch (\NS\A $a) {
    $someThingElse;
}
EOC;

        $parser        = new PhpParser\Parser\Php7(new PhpParser\Lexer\Emulative);
        $prettyPrinter = new PhpParser\PrettyPrinter\Standard;
        $traverser     = new PhpParser\NodeTraverser;
        $traverser->addVisitor(new NameResolver);

        $stmts = $parser->parse($code);
        $stmts = $traverser->traverse($stmts);

        $this->assertSame(
            $this->canonicalize($expectedCode),
            $prettyPrinter->prettyPrint($stmts)
        );
    }

    public function testNoResolveSpecialName() {
        $stmts = [new Node\Expr\New_(new Name('self'))];

        $traverser = new PhpParser\NodeTraverser;
        $traverser->addVisitor(new NameResolver);

        $this->assertEquals($stmts, $traverser->traverse($stmts));
    }

    public function testAddDeclarationNamespacedName() {
        $nsStmts = [
            new Stmt\Class_('A'),
            new Stmt\Interface_('B'),
            new Stmt\Function_('C'),
            new Stmt\Const_([
                new Node\Const_('D', new Node\Scalar\LNumber(42))
            ]),
            new Stmt\Trait_('E'),
            new Expr\New_(new Stmt\Class_(null)),
        ];

        $traverser = new PhpParser\NodeTraverser;
        $traverser->addVisitor(new NameResolver);

        $stmts = $traverser->traverse([new Stmt\Namespace_(new Name('NS'), $nsStmts)]);
        $this->assertSame('NS\\A', (string