<?php
namespace Hamcrest;

/*
 Copyright (c) 2009 hamcrest.org
 */

/**
 * A matcher over acceptable values.
 * A matcher is able to describe itself to give feedback when it fails.
 * <p/>
 * Matcher implementations should <b>NOT directly implement this interface</b>.
 * Instead, <b>extend</b> the {@link Hamcrest\BaseMatcher} abstract class,
 * which will ensure that the Matcher API can grow to support
 * new features and remain compatible with all Matcher implementations.
 * <p/>
 * For easy access to common Matcher implementations, use the static factory
 * methods in {@link Hamcrest\CoreMatchers}.
 *
 * @see Hamcrest\CoreMatchers
 * @see Hamcrest\BaseMatcher
 */
interface Matcher extends SelfDescribing
{

    /**
     * Evaluates the matcher for argument <var>$item</var>.
     *
     * @param mixed $item the object against which the matcher is evaluated.
     *
     * @return boolean <code>true</code> if <var>$item</var> matches,
     *   otherwise <code>false</code>.
     *
     * @see Hamcrest\BaseMatcher
     */
    public function matches($item);

        /**
         * Generate a description of why the matcher has not accepted the item.
         * The description will be part of a larger d