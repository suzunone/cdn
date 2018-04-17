<?php
/**
 * @package    Suzunone\CDN
 * @subpackage Config
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 */

namespace Suzunone\CDN\Config;

/**
 * @package    Suzunone\CDN
 * @subpackage Config
 * @author     Suzunone <suzunone.eleven@gmail.com>
 * @copyright  Suzunone 2018
 * @license    BSD 2-Clause License
 * @link       https://github.com/suzunone/CDN
 * @see        https://github.com/suzunone/CDN
 * @since      Class available since Release 1.0.0
 */
class Host
{
    /**
     * @var int httpのみ
     */
    const HTTP_ONLY = 1;

    /**
     * @var int httpsのみ
     */
    const HTTPS_ONLY = 2;

    /**
     * @var int リクエストに応じて切り替え
     */
    const MATCH_VIEWER = 3;

    /**
     * すべてFORWARDする
     */
    const FORWARD_ALL = 1;
    /**
     * ホワイトリスト形式で指定する
     */
    const FORWARD_WHITE_LIST = 2;
    /**
     * ORIGINリクエストのprotocol
     *
     * @var int デフォルトはMATCH_VIEWER
     */
    protected $origin_protocol_policy = 3;
    /**
     * @var string httpのポート
     */
    protected $http_port = '80';
    /**
     * @var string httpsのポート
     */
    protected $https_port = '443';
    /**
     * @var array カスタムヘッダの出力
     */
    protected $custom_origin_headers = [];
    /**
     * ユーザーリクエストヘッダの透過設定
     *
     * @var int デフォルトはFORWARD_ALL
     */
    protected $forward_header = 1;

    /**
     * @var array 透過させる ユーザーリクエストヘッダ
     */
    protected $forward_headers_white_list = [];

    /**
     * @var bool trueを指定すると SSL証明書の検証を行うようになります
     */
    protected $ssl_verify_peer = false;

    /**
     * @var int タイムアウト時間
     */
    protected $request_time_out = 45;

    /**
     * SSL証明書の検証をするかどうか
     *
     * @return bool
     */
    public function getSSLVerifyPeer(): bool
    {
        return $this->ssl_verify_peer;
    }

    /**
     * SSL証明書の検証をするかどうか
     *
     * @param bool $ssl_verify_peer
     *
     * @return Host
     */
    public function setSSLVerifyPeer(bool $ssl_verify_peer)
    {
        $this->ssl_verify_peer = $ssl_verify_peer;

        return $this;
    }

    /**
     * タイムアウト時間取得
     *
     * @return int
     */
    public function getRequestTimeOut(): int
    {
        return $this->request_time_out;
    }

    /**
     * タイムアウト時間設定
     *
     * @param int $request_time_out
     *
     * @return Host
     */
    public function setRequestTimeOut(int $request_time_out)
    {
        $this->request_time_out = $request_time_out;

        return $this;
    }

    /**
     * オリジンへの取得ポリシーを取得する
     *
     * @return int
     * @see Host::HTTP_ONLY
     * @see Host::HTTPS_ONLY
     * @see Host::MATCH_VIEWER
     */
    public function getOriginProtocolPolicy(): int
    {
        return $this->origin_protocol_policy;
    }

    /**
     * オリジンへの取得ポリシーをセットする
     *
     * @param int $origin_protocol_policy (Domain::HTTP_ONLY OR Domain::HTTPS_ONLY OR Domain::MATCH_VIEWER)
     *
     * @return self
     * @see Host::HTTP_ONLY
     * @see Host::HTTPS_ONLY
     * @see Host::MATCH_VIEWER
     */
    public function setOriginProtocolPolicy(int $origin_protocol_policy)
    {
        $this->origin_protocol_policy = $origin_protocol_policy;

        return $this;
    }

    /**
     * オリジンへのリクエストでHTTPのリクエストポートを取得する
     *
     * @return string
     */
    public function getHttpPort(): string
    {
        return $this->http_port;
    }

    /**
     * オリジンへのリクエストでHTTPのリクエストポートを設定する
     *
     * @param string $http_port
     * @return self
     */
    public function setHttpPort(string $http_port)
    {
        $this->http_port = $http_port;

        return $this;
    }

    /**
     * オリジンへのリクエストでHTTPSのリクエストポートを取得する
     *
     * @return string
     */
    public function getHttpsPort(): string
    {
        return $this->https_port;
    }

    /**
     * オリジンへのリクエストでHTTPSのリクエストポートを設定する
     *
     * @param string $https_port
     * @return self
     */
    public function setHttpsPort(string $https_port)
    {
        $this->https_port = $https_port;

        return $this;
    }

    /**
     * カスタムヘッダを返す
     *
     * @return array
     */
    public function getCustomOriginHeaders(): array
    {
        return $this->custom_origin_headers;
    }

    /**
     * カスタムヘッダをセットする
     *
     * @param string $custom_origin_header
     * @param string|null $values
     *
     * @return self
     */
    public function setCustomOriginHeader(string $custom_origin_header, string $values = null)
    {
        $this->custom_origin_headers[$custom_origin_header] = $custom_origin_header;

        if (isset($values)) {
            $this->custom_origin_headers[$custom_origin_header] = $custom_origin_header . ': ' . $values;
        }

        return $this;
    }

    /**
     * ブラウザからのリクエストヘッダの透過設定を返す
     *
     * @return int
     * @see Host::FORWARD_ALL
     * @see Host::FORWARD_WHITE_LIST
     */
    public function getForwardHeader(): int
    {
        return $this->forward_header;
    }

    /**
     * ブラウザからのリクエストヘッダの透過設定を指定する
     *
     * @param int $forward_header
     *
     * @return self
     * @see Host::FORWARD_ALL
     * @see Host::FORWARD_WHITE_LIST
     */
    public function setForwardHeader(int $forward_header)
    {
        $this->forward_header = $forward_header;

        return $this;
    }

    /**
     * ブラウザからのリクエストヘッダの透過するヘッダを返す
     *
     * @return array
     */
    public function getForwardHeadersWhiteList(): array
    {
        return $this->forward_headers_white_list;
    }

    /**
     * ブラウザからのリクエストヘッダの透過設定をセットする
     *
     * @param string $forward_headers_white_list
     * @return self
     */
    public function setForwardHeadersWhiteList(string $forward_headers_white_list)
    {
        $this->forward_headers_white_list[strtolower($forward_headers_white_list)] = $forward_headers_white_list;

        return $this;
    }
}
