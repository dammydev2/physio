CarbonInterface::TRANSLATE_MONTHS
     *                                - CarbonInterface::TRANSLATE_DAYS
     *                                - CarbonInterface::TRANSLATE_UNITS
     *                                - CarbonInterface::TRANSLATE_MERIDIEM
     *                                You can use pipe to group: CarbonInterface::TRANSLATE_MONTHS | CarbonInterface::TRANSLATE_DAYS
     *
     * @return string
     */
    public static function translateTimeString($timeString, $from = null, $to = null, $mode = CarbonInterface::TRANSLATE_ALL)
    {
        $from = $from ?: static::getLocale();
        $to = $to ?: 'en';

        if ($from === $to) {
            return $timeString;
        }

        $cleanWord = function ($word) {
            $word = str_replace([':count', '%count', ':time'], '', $word);
            $word = preg_replace('/({\d+(,(\d+|Inf))?}|[\[\]]\d+(,(\d+|Inf))?[\[\]])/', '', $word);

            return trim($word);
        };

        $fromTranslations = [];
        $toTranslations = [];

        foreach (['from', 'to'] as $key) {
            $language = $$key;
            $translator = Translator::get($language);
            $translations = $translator->getMessages();

            if (!isset($translations[$language])) {
                return $timeString;
            }

            $translationKey = $key.'Translations';
            $messages = $translations[$language];
            $months = $messages['months'];
            $weekdays = $messages['weekdays'];
            $meridiem = $messages['meridiem'] ?? ['AM', 'PM'];

            if ($key === 'from') {
                foreach (['months', 'weekdays'] as $variable) {
                    $list = $messages[$variable.'_standalone'] ?? null;

                    if ($list) {
                        foreach ($$variable as $index => &$name) {
                            $name .= '|'.$messages[$variable.'_standalone'][$index];
                        }
                    }
                }
            }

            $$translationKey = array_merge(
                $mode & CarbonInterface::TRANSLATE_MONTHS ? array_pad($months, 12, '>>DO NOT REPLACE<<') : [],
                $mode & CarbonInterface::TRANSLATE_MONTHS ? array_pad($messages['months_short'], 12, '>>DO NOT REPLACE<<') : [],
                $mode & CarbonInterface::TRANSLATE_DAYS ? array_pad($weekdays, 7, '>>DO NOT REPLACE<<') : [],
                $mode & CarbonInterface::TRANSLATE_DAYS ? array_pad($messages['weekdays_short'], 7, '>>DO NOT REPLACE<<') : [],
                $mode & CarbonInterface::TRANSLATE_UNITS ? array_map(function ($unit) use ($messages, $key, $cleanWord) {
                    $parts = explode('|', $messages[$unit]);

                    return $key === 'to'
                        ? $cleanWord(end($parts))
                        : '(?:'.implode('|', array_map($cleanWord, $parts)).')';
                }, [
                    'year',
                    'month',
                    'week',
                    'day',
                    'hour',
                    'minute',
                    'second',
                ]) : [],
                $mode & CarbonInterface::TRANSLATE_MERIDIEM ? array_map(function ($hour) use ($meridiem) {
                    if (is_array($meridiem)) {
                        return $meridiem[$hour < 12 ? 0 : 1];
                    }

                    return $meridiem($hour, 0, false);
                }, range(0, 23)) : []
            );
        }

        return substr(preg_replace_callback('/(?<=[\d\s+.\/,_-])('.implode('|', $fromTranslations).')(?=[\d\s+.\/,_-])/i', function ($match) use ($fromTranslations, $toTranslations) {
            [$chunk] = $match;

            foreach ($fromTranslations as $index => $word) {
                if (preg_match("/^$word\$/i", $chunk)) {
                    return $toTranslations[$index] ?? '';
                }
            }

            return $chunk; // @codeCoverageIgnore
        }, " $timeString "), 1, -1);
    }

    /**
     * Translate a time string from the current locale (`$date->locale()`) to an other.
     *
     * @param string      $timeString time string to translate
     * @param string|null $to         output locale of the result returned ("en" by default)
     *
     * @return string
     */
    public function translateTimeStringTo($timeString, $to = null)
    {
        return static::translateTimeString($timeString, $this->getLocalTranslator()->getLocale(), $to);
    }

    /**
     * Get/set the locale for the current instance.
     *
     * @param string|null $locale
     * @param string[]    ...$fallbackLocales
     *
     * @return $this|string
     */
    public function locale(string $locale = null, ...$fallbackLocales)
    {
        if ($locale === null) {
            return $this->getLocalTranslator()->getLocale();
        }

        if (!$this->localTranslator || $this->localTranslator->getLocale() !== $locale) {
            $translator = Translator::get($locale);

            if (!empty($fallbackLocales)) {
                $translator->setFallbackLocales($fallbackLocales);

                foreach ($fallbackLocales as $fallbackLocale) {
                    $messages = Translator::get($fallbackLocale)->getMessages();

                    if (isset($messages[$fallbackLocale])) {
                        $translator->setMessages($fallbackLocale, $messages[$fallbackLocale]);
                    }
                }
            }

            $this->setLocalTranslator($translator);
        }

        return $this;
    }

    /**
     * Get the current translator locale.
     *
     * @return string
     */
    public static function getLocale()
    {
        return static::translator()->getLocale();
    }

    /**
     * Set the current translator locale and indicate if the source locale file exists.
     * Pass 'auto' as locale to use closest language from the current LC_TIME locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function setLocale($locale)
    {
        return static::translator()->setLocale($locale) !== false;
    }

    /**
     * Set the fallback locale.
     *
     * @see https://symfony.com/doc/current/components/translation.html#fallback-locales
     *
     * @param string $locale
     */
    public static function setFallbackLocale($locale)
    {
        $translator = static::getTranslator();

        if (method_exists($translator, 'setFallbackLocales')) {
            $translator->setFallbackLocales([$locale]);

            if ($translator instanceof Translator) {
                $preferredLocale = $translator->getLocale();
                $translator->setMessages($preferredLocale, array_replace_recursive(
                    $translator->getMessages()[$locale] ?? [],
                    Translator::get($locale)->getMessages()[$locale] ?? [],
                    $translator->getMessages($preferredLocale)
                ));
            }
        }
    }

    /**
     * Get the fallback locale.
     *
     * @see https://symfony.com/doc/current/components/translation.html#fallback-locales
     *
     * @return string|null
     */
    public static function getFallbackLocale()
    {
        $translator = static::getTranslator();

        if (method_exists($translator, 'getFallbackLocales')) {
            return $translator->getFallbackLocales()[0] ?? null;
        }

        return null;
    }

    /**
     * Set the current locale to the given, execute the passed function, reset the locale to previous one,
     * then return the result of the closure (or null if the closure was void).
     *
     * @param string   $locale locale ex. en
     * @param callable $func
     *
     * @return mixed
     */
    public static function executeWithLocale($locale, $func)
    {
        $currentLocale = static::getLocale();
        $result = call_user_func($func, static::setLocale($locale) ? static::getLocale() : false, static::translator());
        static::setLocale($currentLocale);

        return $result;
    }

    /**
     * Returns true if the given locale is internally supported and has short-units support.
     * Support is considered enabled if either year, day or hour has a short variant translated.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasShortUnits($locale)
    {
        return static::executeWithLocale($locale, function ($newLocale, TranslatorInterface $translator) {
            return $newLocale &&
                (
                    ($y = static::translateWith($translator, 'y')) !== 'y' &&
                    $y !== static::translateWith($translator, 'year')
                ) || (
                    ($y = static::translateWith($translator, 'd')) !== 'd' &&
                    $y !== static::translateWith($translator, 'day')
                ) || (
                    ($y = static::translateWith($translator, 'h')) !== 'h' &&
                    $y !== static::translateWith($translator, 'hour')
                );
        });
    }

    /**
     * Returns true if the given locale is internally supported and has diff syntax support (ago, from now, before, after).
     * Support is considered enabled if the 4 sentences are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffSyntax($locale)
    {
        return static::executeWithLocale($locale, function ($newLocale, TranslatorInterface $translator) {
            if (!$newLocale) {
                return false;
            }

            foreach (['ago', 'from_now', 'before', 'after'] as $key) {
                if ($translator instanceof TranslatorBagInterface && $translator->getCatalogue($newLocale)->get($key) instanceof Closure) {
                    continue;
                }

                if ($translator->trans($key) === $key) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Returns true if the given locale is internally supported and has words for 1-day diff (just now, yesterday, tomorrow).
     * Support is considered enabled if the 3 words are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffOneDayWords($locale)
    {
        return static::executeWithLocale($locale, function ($newLocale, TranslatorInterface $translator) {
            return $newLocale &&
                $translator->trans('diff_now') !== 'diff_now' &&
                $translator->trans('diff_yesterday') !== 'diff_yesterday' &&
                $translator->trans('diff_tomorrow') !== 'diff_tomorrow';
        });
    }

    /**
     * Returns true if the given locale is internally supported and has words for 2-days diff (before yesterday, after tomorrow).
     * Support is considered enabled if the 2 words are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffTwoDayWords($locale)
    {
        return static::executeWithLocale($locale, function ($newLocale, TranslatorInterface $translator) {
            return $newLocale &&
                $translator->trans('diff_before_yesterday') !== 'diff_before_yesterday' &&
                $translator->trans('diff_after_tomorrow') !== 'diff_after_tomorrow';
        });
    }

    /**
     * Returns true if the given locale is internally supported and has period syntax support (X times, every X, from X, to X).
     * Support is considered enabled if the 4 sentences are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasPeriodSyntax($locale)
    {
        return static::executeWithLocale($locale, function ($newLocale, TranslatorInterface $translator) {
            return $newLocale &&
                $translator->trans('period_recurrences') !== 'period_recurrences' &&
                $translator->trans('period_interval') !== 'period_interval' &&
                $translator->trans('period_start_date') !== 'period_start_date' &&
                $translator->trans('period_end_date') !== 'period_end_date';
        });
    }

    /**
     * Returns the list of internally available locales and already loaded custom locales.
     * (It will ignore custom translator dynamic loading.)
     *
     * @return array
     */
    public static function getAvailableLocales()
    {
        $translator = static::translator();

        return $translator instanceof Translator
            ? $translator->getAvailableLocales()
            : [$translator->getLocale()];
    }

    /**
     * Returns list of Language object for each available locale. This object allow you to get the ISO name, native
     * name, region and variant of the locale.
     *
     * @return Language[]
     */
    public static function getAvailableLocalesInfo()
    {
        $languages = [];
        foreach (static::getAvailableLocales() as $id) {
            $languages[$id] = new Language($id);
        }

        return $languages;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 INDX( 	 k≈Ö             (   Ä  Ë          ’                     p ^          `SJpk’ 1®¥f˝‘©®P†‹<’`SJpk’ 0      d.               B o u n d a r i e s . p h p        p ^          a¬UJpk’ 1®¥f˝‘⁄S†‹<’a¬UJpk’ @      Ë=               C o m p a r i s o n . p h p        p \          a¬UJpk’ 1®¥f˝‘#nU†‹<’a¬UJpk’ 0      €-               C o n v e r t e r . p h p          h X          ≈$XJpk’ 1®¥f˝‘}–W†‹<’≈$XJpk’ p      ©f               C r e a t o r . p h p      h R          áZJpk’ 1®¥f˝‘}–W†‹<’áZJpk’ `     ;Y              D a t e . p h p            p ^          çdJpk’ 1®¥f˝‘	2Z†‹<’çdJpk’ ¿      ª               D i f f e r e n c e . p h p        x b          ˆrfJpk’ 1®¥f˝‘4ï\†‹<’ˆrfJpk’ `      ÔS               L o c a l i z a t i o n . p h p            h T          ˆrfJpk’ 1®¥f˝‘4ï\†‹<’ˆrfJpk’       ó              	 M a c r o . p h p          p \          @’hJpk’ 1®¥f˝‘¿ˆ^†‹<’@’hJpk’ @      ·0              M o d i f i e r s . p h p           p ^          û7kJpk’ 1®¥f˝‘Ya†‹<’û7kJpk’                      M u t a b i l i t y . p h p   !     h X          û7kJpk’ 1®¥f˝‘Uºc†‹<’û7kJpk’ 0      !.               O p t i o n s . p h p "     p Z          ÚômJpk’ 1®¥f˝‘Uºc†‹<’ÚômJpk’        à               R o u n d i n g . p h p       #     x d          ÚômJpk’ 1®¥f˝‘◊f†‹<’ÚômJpk’       …               S e r i a l i z a t i o n . p h p     $     h R          Z¸oJpk  1®¥f˝‘Åh†‹<’Z¸oJpk’       L               T e s t . p h p       %     p \          X_rJpk’ 1®¥f˝‘Åh†‹<’X_rJpk’       M               T i m e s t a m p . p h p     &     h T          X_rJpk’ 1®¥f˝‘V„j†‹<’X_rJpk’ 0      ⁄)              	 U n i t s . p h p     '     h R          '¡tJpk’ 1®¥f˝‘ƒDm†‹<’'¡tJpk’        ı               W e e k . p h p                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

/**
 * Trait Boundaries.
 *
 * startOf, endOf and derived method for each unit.
 *
 * Depends on the following properties:
 *
 * @property int $year
 * @property int $month
 * @property int $daysInMonth
 * @property int $quarter
 *
 * Depends on the following methods:
 *
 * @method $this setTime(int $hour, int $minute, int $second = 0, int $microseconds = 0)
 * @method $this setDate(int $year, int $month, int $day)
 * @method $this addMonths(int $value = 1)
 */
trait Macro
{
    /**
     * The registered macros.
     *
     * @var array
     */
    protected static $globalMacros = [];

    /**
     * The registered generic macros.
     *
     * @var array
     */
    protected static $globalGenericMacros = [];

    /**
     * Register a custom macro.
     *
     * @example
     * ```
     * $userSettings = [
     *   'locale' => 'pt',
     *   'timezone' => 'America/Sao_Paulo',
     * ];
     * Carbon::macro('userFormat', function () use ($userSettings) {
     *   return $this->copy()->locale($userSettings['locale'])->tz($userSettings['timezone'])->calendar();
     * });
     * echo Carbon::yesterday()->hours(11)->userFormat();
     * ```
     *
     * @param string          $name
     * @param object|callable $macro
     *
     * @return void
     */
    public static function macro($name, $macro)
    {
        static::$globalMacros[$name] = $macro;
    }

    /**
     * Remove all macros and generic macros.
     */
    public static function resetMacros()
    {
        static::$globalMacros = [];
        static::$globalGenericMacros = [];
    }

    /**
     * Register a custom macro.
     *
     * @param object|callable $macro
     * @param int             $priority marco with higher priority is tried first
     *
     * @return void
     */
    public static function genericMacro($macro, $priority = 0)
    {
        if (!isset(static::$globalGenericMacros[$priority])) {
            static::$globalGenericMacros[$priority] = [];
            krsort(static::$globalGenericMacros, SORT_NUMERIC);
        }

        static::$globalGenericMacros[$priority][] = $macro;
    }

    /**
     * Mix another object into the class.
     *
     * @example
     * ```
     * Carbon::mixin(new class {
     *   public function addMoon() {
     *     return function () {
     *       return $this->addDays(30);
     *     };
     *   }
     *   public function subMoon() {
     *     return function () {
     *       return $this->subDays(30);
     *     };
     *   }
     * });
     * $fullMoon = Carbon::create('2018-12-22');
     * $nextFullMoon = $fullMoon->addMoon();
     * $blackMoon = Carbon::create('2019-01-06');
     * $previousBlackMoon = $blackMoon->subMoon();
     * echo "$nextFullMoon\n";
     * echo "$previousBlackMoon\n";
     * ```
     *
     * @param object $mixin
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public static function mixin($mixin)
    {
        $methods = (new \ReflectionClass($mixin))->getMethods(
            \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);

            static::macro($method->name, $method->invoke($mixin));
        }
    }

    /**
     * Checks if macro is registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$globalMacros[$name]);
    }
}
                                                                                                                                                                                                                                                                                                                                                                         <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;

/**
 * Trait Modifiers.
 *
 * Returns dates relative to current date using modifier short-hand.
 */
trait Modifiers
{
    /**
     * Midday/noon hour.
     *
     * @var int
     */
    protected static $midDayAt = 12;

    /**
     * get midday/noon hour
     *
     * @return int
     */
    public static function getMidDayAt()
    {
        return static::$midDayAt;
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather consider mid-day is always 12pm, then if you need to test if it's an other
     *             hour, test it explicitly:
     *                 $date->format('G') == 13
     *             or to set explicitly to a given hour:
     *                 $date->setTime(13, 0, 0, 0)
     *
     * Set midday/noon hour
     *
     * @param int $hour midday hour
     *
     * @return void
     */
    public static function setMidDayAt($hour)
    {
        static::$midDayAt = $hour;
    }

    /**
     * Modify to midday, default to self::$midDayAt
     *
     * @return static|CarbonInterface
     */
    public function midDay()
    {
        return $this->setTime(static::$midDayAt, 0, 0, 0);
    }

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use th