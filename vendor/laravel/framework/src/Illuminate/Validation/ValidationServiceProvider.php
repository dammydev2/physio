<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesConditionals
{
    /**
     * Identifier for the first case in switch statement.
     *
     * @var bool
     */
    protected $firstCaseInSwitch = true;

    /**
     * Compile the if-auth statements into valid PHP.
     *
     * @param  string|null  $guard
     * @return string
     */
    protected function compileAuth($guard = null)
    {
        $guard = is_null($guard) ? '()' : $guard;

        return "<?php if(auth()->guard{$guard}->check()): ?>";
    }

    /**
     * Compile the else-auth statements into valid PHP.
     *
     * @param  string|null  $guard
     * @return string
     */
    protected function compileElseAuth($guard = null)
    {
        $guard = is_null($guard) ? '()' : $guard;

        return "<?php elseif(auth()->guard{$guard}->check()): ?>";
    }

    /**
     * Compile the end-auth statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndAuth()
    {
        return '<?php endif; ?>';
    }

    /**
     * Compile the if-guest statements into valid PHP.
     *
     * @param  string|null  $guard
     * @return string
     */
    protected function compileGuest($guard = null)
    {
        $guard = is_null($guard) ? '()' : $guard;

        return "<?php if(auth()->guard{$guard}->guest()): ?>";
    }

    /**
     * Compile the else-guest statements into valid PHP.
     *
     * @param  string|null  $guard
     * @return string
     */
    protected function compileElseGuest($guard = null)
    {
        $guard = is_null($guard) ? '()' : $guard;

        return "<?php elseif(auth()->guard{$guard}->guest()): ?>";
    }

    /**
     * Compile the