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

use Suzunone\CDN\Config\Host as HostConfig;
use Suzunone\CDN\Http\Request;
use Suzunone\CDN\Http\Response;

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
class Main
{
    /**
     * @var Request
     */
    protected $Request;

    /**
     * Main constructor.
     */
    public function __construct()
    {
        $this->Request = new Request();
    }

    /**
     * ブラウザから実行される処理
     *
     * @throws \ErrorException
     */
    public function execute()
    {
        // オリジンcontentを取得する
        $Client = $this->Request->getOriginContents();

        $Response = new Response();

        $Response->sendHeader($Client);
        $Response->sendBody($Client);
    }

    /**
     * ホスト名の取得
     *
     * @return string|null
     */
    public function getHostName()
    {
        return $this->Request->getHostName();
    }

    /**
     * ホスト名のセット
     *
     * @param string $host_name
     * @return self
     */
    public function setHostName(string $host_name)
    {
        $this->Request->setHostName($host_name);

        return $this;
    }

    /**
     * きれいなURLが使えない場合、除外するルートパス(getter)
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->Request->getRootPath();
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
        $this->Request->setRootPath($root_path);

        return $this;
    }

    /**
     * 許可するホスト名に追加する
     *
     * @param $host_name string 追加するホスト名
     * @param HostConfig|null $setting デフォルトの設定でいい場合は省略
     *
     * @return self
     * @see \Suzunone\CDN\Config\Host
     */
    public function setAllowHostName(string $host_name, HostConfig $setting = null)
    {
        $this->Request->setAllowHostName($host_name, $setting);

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
        return $this->Request->hasAllowHostName($host_name);
    }
}
