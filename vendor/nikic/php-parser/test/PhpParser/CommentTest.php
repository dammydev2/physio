->expectException(\PhpParser\Error::class);
        $this->expectExceptionMessage($errorMsg);

        $traverser = new PhpParser\NodeTraverser;
        $traverser->addVisitor(new NameResolver);
        $traverser->traverse([$stmt]);
    }

    public function provideTestError() {
        return [
            [
                new Stmt\Use_([
                    new Stmt\UseUse(new Name('A\B'), 'B', 0, ['startLine' => 1]),
                    new Stmt\UseUse(new Name('C\D'), 'B', 0, ['startLine' => 2]),
                ], Stmt\Use_::TYPE_NORMAL),
                'Cannot use C\D as B because the name is already in use on line 2'
            ],
            [
                new Stmt\Use_([
                    new Stmt\UseUse(new Name('a\b'), 'b', 0, ['startLine' => 1]),
                    new Stmt\UseUse(new Name('c\d'), 'B', 0, ['startLine' => 2]),
                ], Stmt\Use_::TYPE_FUNCTION),
                'Cannot use function c\d as B because the name is already in use on line 2'
            ],
            [
                new Stmt\Use_([
                    new Stmt\UseUse(new Name('A\B'), 'B', 0, ['startLine' => 1]),
                    new Stmt\UseUse(new Name('C\D'), 'B', 0, ['startLine' => 2]),
                ], Stmt\Use_::TYPE_CONSTANT),
                'Cannot use const C\D as B because the name is already in use on line 2'
            ],
            [
                new Expr\New_(new Name\FullyQualified('self', ['startLine' => 3])),
                "'\\self' is an invalid class name on line 3"
            ],
            [
                new Expr\New_(new Name\Relative('self', ['startLine' => 3])),
                "'\\self' is an invalid class name on line 3"
            ],
            [
                new Expr\New_(new Name\FullyQualified('