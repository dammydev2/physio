 function getValid()
    {
        $valid = [
            // class declarations
            ['class Psy_Test_CodeCleaner_ValidClassNamePass_Epsilon {}'],
            ['namespace Psy\Test\CodeCleaner\ValidClassNamePass; class Zeta {}'],
            ['
                namespace { class Psy_Test_CodeCleaner_ValidClassNamePass_Eta {}; }
                namespace Psy\\Test\\CodeCleaner\\ValidClassNamePass {
                    class Psy_Test_CodeCleaner_ValidClassNamePass_Eta {}
                }
            '],
            ['namespace Psy\Test\CodeCleaner\ValidClassNamePass { class stdClass {} }'],

            // class instantiations
            ['new stdClass();'],
            ['new stdClass();'],
            ['
                namespace Psy\\Test\\CodeCleaner\\ValidClassNamePass {
                    class Theta {}
                }
                namespace Psy\\Test\\CodeCleaner\\ValidClassNamePass {
                    new Theta();
                }
            '],
            ['
                namespace Psy\\Test\\CodeCleaner\\ValidClassNamePass {
                    class Iota {}
                    new Iota();
                }
            '],
            ['
                namespace Psy\\Test\\CodeCleaner\\ValidClassNamePass {
                    class Kappa {}
                }
                namespace {
             