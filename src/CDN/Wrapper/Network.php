<?php
/**
 * @noinspection PhpMethodNamingConventionInspection
 * @package      Suzunone\CDN
 * @subpackage   Wrapper
 * @author       Suzunone <suzunone.eleven@gmail.com>
 * @copyright    Suzunone 2018
 * @license      BSD 2-Clause License
 * @link         https://github.com/suzunone/CDN
 * @see          https://github.com/suzunone/CDN
 * @since        Class available since Release 1.0.0
 */

namespace Suzunone\CDN\Wrapper;

/**
 * @package    Suzunone\CDN
 * @subpackage Wrapper
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 * @codeCoverageIgnore
 */
class Network
{
    /**
     * @param array ...$param
     */
    public static function header(...$param)
    {
        call_user_func_array('header', $param);
    }

    /**
     * @param array ...$param
     *
     * @return mixed
     */
    public static function stream_context_create(...$param)
    {
        return call_user_func_array('stream_context_create', $param);
    }
}
