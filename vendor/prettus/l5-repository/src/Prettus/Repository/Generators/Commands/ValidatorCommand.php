<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

use XdgBaseDir\Xdg;

/**
 * A Psy Shell configuration path helper.
 */
class ConfigPaths
{
    /**
     * Get potential config directory paths.
     *
     * Returns `~/.psysh`, `%APPDATA%/PsySH` (when on Windows), and all
     * XDG Base Directory config directories:
     *
     *     http://standards.freedesktop.org/basedir-spec/basedir-spec-latest.html
     *
     * @return string[]
     */
    public static function getConfigDirs()
    {
        $xdg = new Xdg();

        return self::getDirNames($xdg->getConfigDirs());
    }

    /**
     * Get potential home config directory paths.
     *
     * Returns `~/.psysh`, `%APPDATA%/PsySH` (when on Windows), and the
     * XDG Base Directory home config directory:
     *
     *     http://standards.freedesktop.org/basedir-spec/basedir-spec-latest.html
     *
     * @return string[]
     */
    public static function getHomeConfigDirs()
    {
        $xdg = new Xdg();

        return self::getDirNames([$xdg->getHomeConfigDir()]);
    }

    /**
     * Get the current home config directory.
     *
     * Returns the highest precedence home config directory which actually
     * exists. If none of them exists, returns the highest precedence home
     * config directory (`%APPDATA%/PsySH` on Windows, `~/.config/psysh`
     * everywhere else).
     *
     * @see self::getHomeConfigDirs
     *
     * @return string
     */
    public static function getCurrentConfigDir()
    {
        $configDirs = self::getHomeConfigDirs();
        foreach ($configDirs as $configDir) {
            if (@\is_dir($configDir)) {
                return $configDir;
            }
        }

        return $configDirs[0];
    }

    /**
     * Find real config files in config directories.
     *
     * @param string[] $names     Config file names
     * @param string   $configDir Optionally use a specific config directory
     *
     * @return string[]
     */
    public static function getConfigFiles(array $names, $configDir = null)
    {
        $dirs = ($configDir === null) ? self::getConfigDirs() : [$configDir];

        return self::getRealFiles($dirs, $names);
    }

    /**
     * Get potential data d