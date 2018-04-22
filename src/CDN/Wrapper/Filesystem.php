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
class Filesystem
{
    public static $http_response_header;

    /**
     * @param array ...$param
     *
     * @return mixed
     */
    public static function file_get_contents(...$param)
    {
        $http_response_header = null;
        $res = @call_user_func_array('file_get_contents', $param);

        static::$http_response_header = $http_response_header ?? null;

        return $res;
    }
}
