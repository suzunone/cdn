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

namespace Tests\Suzunone\CDN\Http;

use Faker\Factory as FakerFactory;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Config\Host as HostConfig;
use Suzunone\CDN\Http\Request;

// use Faker\Generator as FakerGenerator;

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
class RequestTest extends TestCase
{
    use \Tests\Suzunone\CDN\InvokeTrait;

    protected $host_name = 'ja.wikipedia.org';

    protected $root_path = '/cdn.php';

    protected $server;

    public function setUp()
    {
        parent::setUp();

        $this->server = $_SERVER;
    }

    public function tearDown()
    {
        parent::tearDown();

        $_SERVER = $this->server;
    }

    /**
     *
     * @covers              \Suzunone\CDN\Http\Request::getPostData()
     * @throws \ReflectionException
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_getPostData()
    {
        $post_data   = 'aaa=bbb&ccc=ddd';
        $checkerMock = m::mock('alias:\Filesystem');
        $checkerMock
            ->shouldReceive('file_get_contents')
            ->once()
            ->with('php://input')
            ->andReturn($post_data);

        $Request           = new Request();
        $post_data_testing = $this->invokeExecuteMethod($Request, 'getPostData', []);

        $this->assertEquals($post_data, $post_data_testing);
    }

    /**
     * @covers \Suzunone\CDN\Http\Request::setAllowHostName()
     * @throws \ReflectionException
     */
    public function test_setAllowHostName()
    {
        $request = new Request();

        $host_config = new HostConfig();
        $request->setAllowHostName($this->host_name, $host_config);

        $this->assertArrayHasKey(
            $this->host_name,
            $this->invokeGetProperty($request, 'allow_host_names')
        );

        $this->assertEquals(
            [$this->host_name => $host_config],
            $this->invokeGetProperty($request, 'allow_host_names')
        );

        return $request;
    }

    /**
     * @covers \Suzunone\CDN\Http\Request::setHostName()
     */
    public function test_setHostName()
    {
        $request = new Request();

        $request->setHostName($this->host_name);

        return $request;
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::hasAllowHostName()
     * @depends test_setAllowHostName
     * @param \Suzunone\CDN\Http\Request $request
     */
    public function test_hasAllowHostName(Request $request)
    {
        $this->assertTrue($request->hasAllowHostName($this->host_name));

        $this->assertFalse($request->hasAllowHostName('unknown.org'));
    }

    /**
     * @covers \Suzunone\CDN\Http\Request::setRootPath()
     * @throws \ReflectionException
     */
    public function test_setRootPath()
    {
        $request = new Request();
        $request->setRootPath('/index.php/');

        $this->assertEquals(
            '/index.php',
            $this->invokeGetProperty($request, 'root_path')
        );

        $request->setRootPath($this->root_path);

        $this->assertEquals(
            $this->root_path,
            $this->invokeGetProperty($request, 'root_path')
        );

        return $request;
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::getHostConfig()
     * @depends test_setAllowHostName
     * @param \Suzunone\CDN\Http\Request $request
     * @throws \ReflectionException
     */
    public function test_getHostConfig(Request $request)
    {
        $allow_hosts = $this->invokeGetProperty($request, 'allow_host_names');

        $obj = $allow_hosts[$this->host_name];

        $this->assertSame($obj, $request->getHostConfig($this->host_name));

        $this->assertNotSame($obj, $request->getHostConfig('foo.jp'));
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::getHostName()
     * @depends test_setHostName
     * @param \Suzunone\CDN\Http\Request $request
     */
    public function test_getHostName(Request $request)
    {
        $this->assertEquals($this->host_name, $request->getHostName());
    }

    /**
     * @covers              \Suzunone\CDN\Http\Request::getOriginContents()
     * @covers              \Suzunone\CDN\Http\Request::sendRequest()
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws \ErrorException
     */
    public function test_getOriginContents_http()
    {
        $checkerMock = m::mock('alias:\Network');
        $checkerMock
            ->shouldReceive('header')
            ->andReturn(true);

        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'GET',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $Request = new Request();

        $Request->setAllowHostName('httpbin.org')
            ->setRootPath('/index.php');

        $Client   = $Request->getOriginContents();
        $contents = $Client->contents;

        $data = json_decode($contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);
        $this->assertEquals($_SERVER['HTTP_USER_AGENT'], $data['headers']['User-Agent']);
    }

    /**
     * @covers              \Suzunone\CDN\Http\Request::getOriginContents()
     * @covers              \Suzunone\CDN\Http\Request::sendRequest()
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws \ErrorException
     */
    public function test_getOriginContents_https()
    {
        $checkerMock = m::mock('alias:\Network');
        $checkerMock
            ->shouldReceive('header')
            ->andReturn(true);

        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'GET',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTPS'                          => true,
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $Request = new Request();

        $Request->setAllowHostName('httpbin.org')
            ->setRootPath('/index.php');

        $Client   = $Request->getOriginContents();
        $contents = $Client->contents;

        $data = json_decode($contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);
        $this->assertEquals($_SERVER['HTTP_USER_AGENT'], $data['headers']['User-Agent']);
    }

    /**
     * @covers              \Suzunone\CDN\Http\Request::getOriginContents()
     * @covers              \Suzunone\CDN\Http\Request::sendRequest()
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws \ErrorException
     */
    public function test_getOriginContents_http_post()
    {
        $checkerMock = m::mock('alias:\Network');
        $checkerMock
            ->shouldReceive('header')
            ->andReturn(true);

        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $Request = m::mock(
            Request::class . '[getPostData]',
            []
        );

        $post_data = [
            'name'    => $faker->name,
            'address' => $faker->address,
            'text'    => $faker->text,
            'degit'   => $faker->randomDigit,
        ];
        $Request
            // 1. Protectedメソッドをモック
            ->shouldAllowMockingProtectedMethods()
            // 2. モック開始
            ->shouldReceive('getPostData')
            // 3. 実行回数1
            ->once()
            // 4. 返り値
            ->andReturn(http_build_query($post_data));

        /**
         * @var Request $Request
         */
        $Request->setAllowHostName('httpbin.org')
            ->setRootPath('/index.php');

        $Client   = $Request->getOriginContents();
        $contents = $Client->contents;

        $data = json_decode($contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);

        $this->assertEquals($post_data, $data['form']);
        $this->assertEquals('POST', $data['method']);

        $this->assertEquals($_SERVER['HTTP_USER_AGENT'], $data['headers']['User-Agent']);
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::getRootPath()
     * @depends test_setRootPath
     * @param \Suzunone\CDN\Http\Request $request
     */
    public function test_getRootPath(Request $request)
    {
        $this->assertEquals($this->root_path, $request->getRootPath());
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::usingOriginRequestProtocol()
     * @throws \ReflectionException
     */
    public function test_usingOriginRequestProtocol()
    {
        $_SERVER = [
            'SCRIPT_NAME' => '/index.php',
        ];

        $HostConfig = new HostConfig();
        $request    = new Request();

        $HostConfig->setOriginProtocolPolicy(HostConfig::HTTP_ONLY);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('http', $protocol);

        $HostConfig->setOriginProtocolPolicy(HostConfig::HTTPS_ONLY);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('https', $protocol);

        $HostConfig->setOriginProtocolPolicy(HostConfig::MATCH_VIEWER);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('http', $protocol);

        $_SERVER = [
            'SCRIPT_NAME' => '/index.php',
            'HTTPS'       => 1,
        ];

        $HostConfig->setOriginProtocolPolicy(HostConfig::HTTP_ONLY);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('http', $protocol);

        $HostConfig->setOriginProtocolPolicy(HostConfig::HTTPS_ONLY);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('https', $protocol);

        $HostConfig->setOriginProtocolPolicy(HostConfig::MATCH_VIEWER);
        $protocol = $this->invokeExecuteMethod(
            $request,
            'usingOriginRequestProtocol',
            [$HostConfig]
        );

        $this->assertEquals('https', $protocol);
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::createOriginUrl()
     * @throws \ReflectionException
     */
    public function test_createOriginUrl()
    {
        $request = new Request();

        $faker      = FakerFactory::create();
        $url_string = $faker->url;
        $url        = parse_url($url_string);

        $this->invokeSetProperty($request, 'protocol', $url['scheme']);
        $this->invokeSetProperty($request, 'host_name', $url['host']);

        $this->invokeSetProperty($request, 'path', $url['path']);

        $this->assertEquals($url_string, $this->invokeExecuteMethod($request, 'createOriginUrl', []));
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::createRequestHeader()
     * @throws \ReflectionException
     */
    public function test_createRequestHeader_normal()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $HostConfig = new HostConfig();

        $request = new Request();

        $headrs = $this->invokeExecuteMethod($request, 'createRequestHeader', [$HostConfig]);

        $this->assertEquals(
            [
                'upgrade-insecure-requests' => 'upgrade-insecure-requests: ' . $_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'],
                'user-agent'                => 'user-agent: ' . $_SERVER['HTTP_USER_AGENT'],
                'accept'                    => 'accept: ' . $_SERVER['HTTP_ACCEPT'],
                'accept-language'           => 'accept-language: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                'cookie'                    => 'cookie: ' . $_SERVER['HTTP_COOKIE'],
            ],
            $headrs
        );
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::createRequestHeader()
     * @throws \ReflectionException
     */
    public function test_createRequestHeader_limited()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $HostConfig = new HostConfig();
        $HostConfig->setForwardHeader(HostConfig::FORWARD_WHITE_LIST);
        $HostConfig->setForwardHeadersWhiteList('User-Agent');

        $request = new Request();

        $headrs = $this->invokeExecuteMethod($request, 'createRequestHeader', [$HostConfig]);

        $this->assertEquals(
            [
                'user-agent' => 'user-agent: ' . $_SERVER['HTTP_USER_AGENT'],
            ],
            $headrs
        );
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::createRequestHeader()
     * @throws \ReflectionException
     */
    public function test_createRequestHeader_custom()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/httpbin.org/anything',
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/httpbin.org/anything',
            'PHP_SELF'                       => '/index.php/httpbin.org/anything',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $HostConfig = new HostConfig();
        $HostConfig->setForwardHeader(HostConfig::FORWARD_WHITE_LIST);
        $HostConfig->setForwardHeadersWhiteList('User-Agent');
        $HostConfig->setCustomOriginHeader('user-agent', 'wget');
        $HostConfig->setCustomOriginHeader('x-content-test', 'hogehoge');

        $request = new Request();

        $headers = $this->invokeExecuteMethod($request, 'createRequestHeader', [$HostConfig]);

        $this->assertEquals(
            [
                'user-agent'     => 'user-agent: ' . 'wget',
                'x-content-test' => 'x-content-test: hogehoge',
            ],
            $headers
        );
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::parseRequest()
     * @throws \ReflectionException
     */
    public function test_parseRequest_multi_host()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $url_string = $faker->url;
        $url        = parse_url($url_string);

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/' . $url['host'] . $url['path'],
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/' . $url['host'] . $url['path'],
            'PHP_SELF'                       => '/index.php/' . $url['host'] . $url['path'],
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $request = new Request();
        $request->setAllowHostName($url['host']);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);

        $request->setRootPath('/index.php');

        $this->invokeExecuteMethod($request, 'parseRequest', []);

        $this->assertEquals($url['path'], $this->invokeGetProperty($request, 'path'));
        $this->assertEquals($url['host'], $this->invokeGetProperty($request, 'host_name'));
        $this->assertEquals('http', $this->invokeGetProperty($request, 'protocol'));
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::parseRequest()
     * @throws \ReflectionException
     */
    public function test_parseRequest_single_host()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $url_string = $faker->url;
        $url        = parse_url($url_string);

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php' . $url['path'],
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => $url['path'],
            'PHP_SELF'                       => '/index.php' . $url['path'],
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $request = new Request();
        $request->setAllowHostName($url['host']);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setAllowHostName($faker->domainName);
        $request->setHostName($url['host']);

        $request->setRootPath('/index.php');

        $this->invokeExecuteMethod($request, 'parseRequest', []);

        $this->assertEquals($url['path'], $this->invokeGetProperty($request, 'path'));
        $this->assertEquals($url['host'], $this->invokeGetProperty($request, 'host_name'));
        $this->assertEquals('http', $this->invokeGetProperty($request, 'protocol'));
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::parseRequest()
     * @throws \ReflectionException
     * @expectedException \ErrorException
     */
    public function test_parseRequest_error_not_allow()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $url_string = $faker->url;
        $url        = parse_url($url_string);

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php/' . $url['host'] . $url['path'],
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PATH_INFO'                      => '/' . $url['host'] . $url['path'],
            'PHP_SELF'                       => '/index.php/' . $url['host'] . $url['path'],
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $request = new Request();

        $this->invokeExecuteMethod($request, 'parseRequest', []);
    }

    /**
     * @covers  \Suzunone\CDN\Http\Request::parseRequest()
     * @throws \ReflectionException
     * @expectedException \ErrorException
     */
    public function test_parseRequest_error_params()
    {
        $faker = FakerFactory::create();
        $host  = $faker->domainName;

        $_SERVER = [
            'DOCUMENT_ROOT'                  => __DIR__,
            'REMOTE_ADDR'                    => '::1',
            'REMOTE_PORT'                    => '60799',
            'SERVER_SOFTWARE'                => 'PHP 7.0.27 Development Server',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'SERVER_NAME'                    => $host,
            'SERVER_PORT'                    => '8000',
            'REQUEST_URI'                    => '/index.php',
            'REQUEST_METHOD'                 => 'POST',
            'SCRIPT_NAME'                    => '/index.php',
            'SCRIPT_FILENAME'                => __DIR__ . '/index.php',
            'PHP_SELF'                       => '/index.php',
            'HTTP_HOST'                      => 'localhost:8000',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'HTTP_ACCEPT'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE'           => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_COOKIE'                    => 'hoge=123; foo=456; bar=789',
            'HTTP_CACHE_CONTROL'             => 'no-cache',
            'REQUEST_TIME_FLOAT'             => microtime(true),
            'REQUEST_TIME'                   => time(),
        ];

        $request = new Request();

        $this->invokeExecuteMethod($request, 'parseRequest', []);
    }
}
