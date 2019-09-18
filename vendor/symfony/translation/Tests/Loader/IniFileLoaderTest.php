<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Caster;

/**
 * Represents a file or a URL.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class LinkStub extends ConstStub
{
    public $inVendor = false;

    private static $vendorRoots;
    private static $composerRoots;

    public function __construct($label, int $line = 0, $href = null)
    {
        $this->value = $label;

        if (null === $href) {
            $href = $label;
        }
        if (!\is_string($href)) {
            return;
        }
        if (0 === strpos($href, 'file://')) {
            if ($href === $label) {
                $label = substr($label, 7);
            }
            $href = substr($href, 7);
        } elseif (false !== strpos($href, '://')) {
            $this->attr['href'] = $href;

            return;
        }
        if (!file_exists($href)) {
            return;
        }
        if ($line) {
            $this->attr['line'] = $line;
        }
        if ($label !== $this->attr['file'] = realpath($href) ?: $href) {
            return;
        }
        if ($composerRoot = $this->getComposerRoot($href, $this->inVendor)) {
            $this->attr['ellipsis'] = \strlen($href) - \strlen($composerRoot) + 1;
            $this->attr['ellipsis-type'] = 'path';
            $this->attr['ellipsis-tail'] = 1 + ($this->inVendor ? 2 + \strlen(implode('', \array_slice(explode(\DIRECTORY_SEPARATOR, substr($href, 1 - $this->attr