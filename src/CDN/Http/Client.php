<?php
/**
 * HTTPRequestのClient
 *
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

/**
 * HTTPRequestのClient
 *
 * @package    Suzunone\CDN
 * @subpackage Http
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 */
class Client
{
    protected $curl;

    protected $curl_setting = [];

    public $http_response_header;

    public $contents;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();

        // 初期化
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_FAILONERROR, true);
        $this->setOpt(CURLOPT_ENCODING, '');
    }

    /**
     * curl_setoptへのエイリアス
     *
     * @param mixed $key
     * @param mixed $value
     * @see \curl_setopt()
     */
    protected function setOpt($key, $value)
    {
        $this->curl_setting[$key] = $value;

        curl_setopt($this->curl, $key, $value);
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    public function execute(): string
    {
        $full_response = curl_exec($this->curl);

        $error_status = curl_errno($this->curl);
        $error        = curl_error($this->curl);

        if (CURLE_OK !== $error_status) {
            throw new \ErrorException($error, $error_status);
        }

        $info = curl_getinfo($this->curl);

        $this->http_response_header = substr($full_response, 0, $info['header_size']);

        $is_gzip = false;

        if (mb_eregi('Content-Encoding:[ ]*?gzip', $this->http_response_header)) {
            $is_gzip = true;
        }

        $this->http_response_header = preg_split("/[\r\n]+/", $this->http_response_header);

        $contents = mb_substr($full_response, $info['header_size']);

        if ($is_gzip) {
            $contents = ($contents);
        }

        return $this->contents = $contents;
    }

    /**
     * @param bool $setter
     */
    public function setSSLVerifyPeer(bool $setter)
    {
        $this->setOpt(CURLOPT_SSL_VERIFYPEER, $setter);
    }

    /**
     * @param string $setter
     *
     * @return $this
     */
    public function setUrl(string $setter)
    {
        $this->setOpt(CURLOPT_URL, $setter);

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->setOpt(CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    /**
     * @param int $setter
     *
     * @return $this
     */
    public function setTimeOut(int $setter)
    {
        $this->setOpt(CURLOPT_TIMEOUT, $setter);

        return $this;
    }

    /**
     * @param mixed $setter
     *
     * @return $this
     */
    public function setPostData($setter)
    {
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $setter);

        return $this;
    }

    /**
     * ポートを指定する
     *
     * @param int $setter
     *
     * @return $this
     */
    public function setPort(int $setter)
    {
        $this->setOpt(CURLOPT_PORT, $setter);

        return $this;
    }
}
