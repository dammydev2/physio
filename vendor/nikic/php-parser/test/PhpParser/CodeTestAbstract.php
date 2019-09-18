<?php declare(strict_types=1);

namespace PhpParser\NodeVisitor;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;

class NameResolverTest extends \PHPUnit\Framework\TestCase
{
    private function canonicalize($string) {
        return str_replace("\r\n", "\n", $string);
    }

    /**
     * @covers \PhpParser\NodeVisitor\NameResolver
     */
    public function testResolveNames() {
        $code = <<<'EOC'
<?php

namespace Foo {
    use Hallo as Hi;

    new Bar();
    new Hi();
    new Hi\Bar();
    new \Bar();
    new namespace\Bar();

    bar();
    hi();
    Hi\bar();
    foo\bar();
    \bar();
    namespace\bar();
}
namespace {
    use Hallo as Hi;

    new Bar();
    new Hi();
    new Hi\Bar();
    new \Bar();
    new namespace\Bar();

    bar();
    hi();
    Hi\bar();
    foo\bar();
    \bar();
    namespace\bar();
}
namespace Bar {
    use function foo\bar as baz;
    use co