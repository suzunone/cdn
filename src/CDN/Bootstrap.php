<?php
/**
 * @package    Suzunone\CDN
 * @subpackage Main
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since Class available since Release 1.0.0
 */

namespace Suzunone\CDN;

// @codeCoverageIgnoreStart
\class_alias('\Suzunone\CDN\Wrapper\Network', '\Network');
\class_alias('\Suzunone\CDN\Wrapper\Filesystem', '\Filesystem');
// @codeCoverageIgnoreEnd

/**
 * @package    Suzunone\CDN
 * @subpackage Main
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since Class available since Release 1.0.0
 */
class Bootstrap
{
    /**
     * @var Main
     */
    public static $main;

    /**
     * @return Main
     */
    public static function main()
    {
        if (static::$main) {
            return static::$main;
        }

        static::$main = new Main();

        return static::$main;
    }
}
