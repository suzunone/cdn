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

use Filesystem;
use Suzunone\CDN\Config\Host as HostConfig;

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
class Request
{
    /**
     * @var array 許可されているホスト名配列と各ホストの設定
     */
    protected $allow_host_names = [];

    /**
     * @var string オリジンホスト名
     */
    protected $host_name;

    /**
     * @var string オリジンパス
     */
    protected $path = '/';

    /**
     * @var string HTTPのプロトコル
     */
    protected $protocol = 'http';

    /**
     * @var array レスポンスヘッダ
     */
    protected $http_response_header;

    /**
     * @var string きれいなURLが使えない場合、除外するルートパス デフォルトはきれいなURL
     */
    protected $root_path = '';

    /**
     * ホスト名のセット
     *
     * @return string|null
     */
    public function getHostName()
    {
        return $this->host_name;
    }

    /**
     * ホスト名のセット
     *
     * @param string $host_name
     *
     * @return self
     */
    public function setHostName(string $host_name)
    {
        $this->host_name = $host_name;

        return $this;
    }

    /**
     * きれいなURLが使えない場合、除外するルートパス(getter)
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->root_path;
    }

    /**
     * きれいなURLが使えない場合、除外するルートパス(setter)
     *
     * @param string $root_path
     *
     * @return self
     */
    public function setRootPath(string $root_path)
    {
        $this->root_path = rtrim($root_path, '/');

        return $this;
    }

    /**
     * 許可するホスト名に追加する
     *
     * @param $host_name               string 追加するホスト名
     * @param HostConfig|null $setting デフォルトの設定でいい場合は省略
     *
     * @return self
     * @see \Suzunone\CDN\Config\Host
     */
    public function setAllowHostName(string $host_name, HostConfig $setting = null)
    {
        $this->allow_host_names[$host_name] = $setting ?? new HostConfig();

        return $this;
    }

    /**
     * ホスト名が許可されているかどうか
     *
     * @param string $host_name 確認するホスト名
     *
     * @return bool
     */
    public function hasAllowHostName(string $host_name): bool
    {
        return isset($this->allow_host_names[$host_name]);
    }

    /**
     * @return Client
     * @throws \ErrorException
     */
    public function getOriginContents(): Client
    {
        $this->parseRequest();

        return $this->sendRequest();
    }

    /**
     * リクエストをパースする
     *
     * @throws \ErrorException
     */
    protected function parseRequest()
    {
        $request_uri = substr($_SERVER ['REQUEST_URI'], strlen($this->root_path));
        if ($this->host_name) {
            $this->path = $request_uri;

            return;
        }

        $explode = explode('/', $request_uri, 3);

        if (count($explode) !== 3) {
            throw new \ErrorException('undefined request ' . $_SERVER ['REQUEST_URI']);
        }

        list(, $host, $path) = $explode;

        if (!isset($this->allow_host_names[$host])) {
            throw new \ErrorException('undefined domain ' . $host);
        }

        $this->host_name = $host;

        $this->path = '/' . $path;

        $HostConfig     = $this->allow_host_names[$this->host_name];
        $this->protocol = $this->usingOriginRequestProtocol($HostConfig);
    }

    /**
     * originリクエストに使用するProtocolの取得
     *
     * @param HostConfig $HostConfig
     *
     * @return string
     */
    protected function usingOriginRequestProtocol(HostConfig $HostConfig)
    {
        if ($HostConfig->getOriginProtocolPolicy() == HostConfig::HTTP_ONLY) {
            return 'http';
        }

        if ($HostConfig->getOriginProtocolPolicy() == HostConfig::HTTPS_ONLY) {
            return 'https';
        }

        $http = 'http';
        if (isset($_SERVER['HTTPS'])) {
            $http .= 's';
        }

        return $http;
    }

    /**
     * Requestの送信
     *
     * @return Client
     * @throws \ErrorException
     */
    protected function sendRequest(): Client
    {
        /**
         * @var HostConfig $HostConfig
         */
        $HostConfig = $this->getHostConfig($this->host_name);

        $Client = new Client();

        // ヘッダ
        $headers = $this->createRequestHeader($HostConfig);
        $Client->setHeaders($headers);

        // URL
        $url = $this->createOriginUrl();
        $Client->setUrl($url);

        // 各種プロトコルごとの設定
        if ($this->protocol === 'http') {
            $Client->setPort($HostConfig->getHttpPort());
        } elseif ($this->protocol === 'https') {
            $Client->setPort($HostConfig->getHttpsPort());
            $Client->setSSLVerifyPeer($HostConfig->getSSLVerifyPeer());
        }

        // タイムアウト
        $Client->setTimeOut($HostConfig->getRequestTimeOut());

        // ポストする
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Client->setPostData($this->getPostData());
        }

        // リクエストする
        $Client->execute();

        return $Client;
    }

    /**
     * ホストコンフィグの取得
     *
     * @param string $host_name
     * @return HostConfig
     */
    public function getHostConfig(string $host_name): HostConfig
    {
        return $this->allow_host_names[$host_name] ?? new HostConfig();
    }

    /**
     * @param HostConfig $HostConfig
     *
     * @return array
     */
    protected function createRequestHeader(HostConfig $HostConfig)
    {
        // リクエストヘッダ
        $header = [];

        $is_forward_all = $HostConfig->getForwardHeader() == $HostConfig::FORWARD_ALL;
        $white_list     = $HostConfig->getForwardHeadersWhiteList();

        $black_list = [
            'HTTP_HOST'            => 'HTTP_HOST',
            'HTTP_CONNECTION'      => 'HTTP_CONNECTION',
            'HTTP_CACHE_CONTROL'   => 'HTTP_CACHE_CONTROL',
            'HTTP_ACCEPT_ENCODING' => 'HTTP_ACCEPT_ENCODING',
        ];
        foreach ($_SERVER as $key => $value) {
            if (isset($black_list[$key])) {
                continue;
            } elseif (strpos($key, 'HTTP_') !== 0) {
                continue;
            }
            $key = strtolower(str_replace('_', '-', substr($key, 5)));

            // 透過設定の確認
            if (!$is_forward_all && !isset($white_list[$key])) {
                continue;
            }

            $header[$key] = $key . ': ' . $value;
        }

        // カスタムヘッダを付ける
        foreach ($HostConfig->getCustomOriginHeaders() as $key => $value) {
            $key = strtolower($key);

            $header[$key] = $value;
        }

        return $header;
    }

    /**
     * オリジンのURLを取得する
     *
     * @return string オリジンURL
     */
    protected function createOriginUrl()
    {
        $url = $this->protocol . '://' . $this->host_name . $this->path;

        return $url;
    }

    /**
     * ポストデータ取得
     *
     * @return string
     */
    protected function getPostData(): string
    {
        return Filesystem::file_get_contents('php://input');
    }
}
