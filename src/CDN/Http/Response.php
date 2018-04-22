<?php
/**
 * @package    Suzunone\CDN
 * @subpackage Http
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 */

namespace Suzunone\CDN\Http;

use Network;

/**
 * @package    Suzunone\CDN
 * @subpackage Http
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 */
class Response
{
    /**
     * Bodyを送信する
     *
     * @param Client $Client
     */
    public function sendBody(Client $Client)
    {
        echo $Client->contents;
    }

    /**
     * ヘッダを送信する
     *
     * @param Client $Client
     */
    public function sendHeader(Client $Client)
    {
        $http_response_header = $Client->http_response_header;

        foreach ($this->parseHeader($http_response_header) as $header) {
            Network::header($header);
        }
    }

    /**
     * 戻り値のヘッダを解析して、そのまま返す
     *
     * @param array $http_response_header
     *
     * @return \Generator ヘッダ文字列
     */
    protected function parseHeader(array $http_response_header)
    {
        $last_http_200 = null;

        foreach ($http_response_header as $key => $value) {
            if (!isset($last_http_200) && strtoupper($value) === 'HTTP/1.1 200 OK') {
                $last_http_200 = $key;
                continue;
            }

            // 200OKが出るまで無視し続ける
            if (!isset($last_http_200)) {
                continue;
            }

            // Pragmaは無視する
            if (stripos($value, 'Pragma:') === 0) {
                continue;
            }

            if (strpos($value, ':')) {
                yield $value;
            }
        }
    }
}
