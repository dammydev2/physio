<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation;

use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class DataCollectorTranslator implements LegacyTranslatorInterface, TranslatorInterface, TranslatorBagInterface, WarmableInterface
{
    const MESSAGE_DEFINED = 0;
    const MESSAGE_MISSING = 1;
    const MESSAGE_EQUALS_FALLBACK = 2;

    /**
     * @var TranslatorInterface|TranslatorBagInterface
     */
    private $translator;

    private $messages = [];

    /**
     * @param TranslatorInterface $translator The translator must implement TranslatorBagInterface
     */
    public function __construct($translator)
    {
        if (!$translator instanceof LegacyTranslatorInterface && !$translator instanceof TranslatorInterface) {
            throw new \TypeError(sprintf('Argument 1 passed to %s() must be an instance of %s, %s given.', __METHOD__, TranslatorInterface::class, \is_object($translator) ? \get_cla