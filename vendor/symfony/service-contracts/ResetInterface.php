<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Dumper;

use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * XliffFileDumper generates xliff files from a message catalogue.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class XliffFileDumper extends FileDumper
{
    /**
     * {@inheritdoc}
     */
    public function formatCatalogue(MessageCatalogue $messages, $domain, array $options = [])
    {
        $xliffVersion = '1.2';
        if (\array_key_exists('xliff_version', $options)) {
            $xliffVersion = $options['xliff_version'];
        }

        if (\array_key_exists('default_locale', $options)) {
            $defaultLocale = $options['default_locale'];
      