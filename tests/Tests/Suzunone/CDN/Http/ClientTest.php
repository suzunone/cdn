<?php
/**
 * Class ClientTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */

namespace Tests\Suzunone\CDN\Http;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Http\Client;

/**
 * Class ClientTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */
class ClientTest extends TestCase
{
    use \Tests\Suzunone\CDN\InvokeTrait;

    const URI_404 = 'https://httpbin.org/status/404';
    const URI_500 = 'https://httpbin.org/status/500';

    const URL_DEFLATE = 'https://httpbin.org/deflate';
    const URL_GZIP = 'https://httpbin.org/gzip';
    const URL_ANYTHING = 'https://httpbin.org/anything';

    /**
     * @covers \Suzunone\CDN\Http\Client::__construct()
     * @throws \ReflectionException
     */
    public function test_construct()
    {
        $Client = new Client();

        $this->assertTrue(is_resource($this->invokeGetProperty($Client, 'curl')));
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setUrl()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setUrl()
    {
        $faker = FakerFactory::create();

        $url = $faker->url;

        $Client = new Client();

        $Client->setUrl($url);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($url, $info[CURLOPT_URL]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setSSLVerifyPeer()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setSSLVerifyPeer()
    {
        $faker = FakerFactory::create();

        $boolean = $faker->boolean;

        $Client = new Client();

        $Client->setSSLVerifyPeer($boolean);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($boolean, $info[CURLOPT_SSL_VERIFYPEER]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setTimeOut()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setTimeOut()
    {
        $faker = FakerFactory::create();

        $digit = $faker->randomDigitNotNull;

        $Client = new Client();

        $Client->setTimeOut($digit);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($digit, $info[CURLOPT_TIMEOUT]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setPort()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setPort()
    {
        $faker = FakerFactory::create();

        $digit = $faker->randomDigitNotNull;

        $Client = new Client();

        $Client->setPort($digit);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($digit, $info[CURLOPT_PORT]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setHeaders()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setHeaders()
    {
        $Client = new Client();

        $headers = ['x-request-aaa' => 'test'];
        $Client->setHeaders($headers);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($headers, $info[CURLOPT_HTTPHEADER]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::setPostData()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ReflectionException
     */
    public function test_setPostData()
    {
        $faker = FakerFactory::create();

        $post_data = $faker->randomHtml(2, 3);

        $Client = new Client();

        $Client->setPostData($post_data);

        $info = $this->invokeGetProperty($Client, 'curl_setting');

        $this->assertEquals($post_data, $info[CURLOPT_POSTFIELDS]);
        $this->assertTrue($info[CURLOPT_POST]);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::execute()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ErrorException
     */
    public function test_execute_google()
    {
        $faker  = FakerFactory::create();
        $Client = new Client();

        $headers = ['user-agent: ' . $faker->userAgent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(1);
        $Client->setUrl('https://www.google.co.jp');

        $Client->execute();

        $this->assertEquals('HTTP/1.1 200 OK', $Client->http_response_header[0]);

        $this->assertTrue(stripos($Client->contents, 'google.co.jp') != false);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::execute()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ErrorException
     */
    public function test_execute_php_net()
    {
        $faker  = FakerFactory::create();
        $Client = new Client();

        $headers = ['user-agent: ' . $faker->userAgent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(1);
        $Client->setUrl('http://php.net/');

        $Client->execute();

        $this->assertEquals('HTTP/1.1 200 OK', $Client->http_response_header[0]);

        $this->assertTrue(stripos($Client->contents, 'php.net') != false);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::execute()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ErrorException
     */
    public function test_execute_anything()
    {
        $Client = new Client();

        $faker      = FakerFactory::create();
        $user_agent = $faker->userAgent;

        $headers = ['user-agent: ' . $user_agent];
        $Client->setHeaders($headers);

        $Client->setTimeOut(3);
        $Client->setUrl(static::URL_ANYTHING);

        $Client->execute();

        $this->assertEquals('HTTP/1.1 200 OK', $Client->http_response_header[0]);

        $data = json_decode($Client->contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);
        $this->assertEquals($user_agent, $data['headers']['User-Agent']);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::execute()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ErrorException
     */
    public function test_execute_gzip()
    {
        $Client = new Client();

        $faker      = FakerFactory::create();
        $user_agent = $faker->userAgent;

        $headers = ['user-agent: ' . $user_agent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(3);
        $Client->setUrl(static::URL_GZIP);

        $Client->execute();

        $this->assertEquals('HTTP/1.1 200 OK', $Client->http_response_header[0]);

        $data = json_decode($Client->contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);
        $this->assertEquals($user_agent, $data['headers']['User-Agent']);
    }

    /**
     * @covers \Suzunone\CDN\Http\Client::execute()
     * @covers \Suzunone\CDN\Http\Client::setOpt()
     * @throws \ErrorException
     */
    public function test_execute_deflate()
    {
        $Client = new Client();

        $faker      = FakerFactory::create();
        $user_agent = $faker->userAgent;

        $headers = ['user-agent: ' . $user_agent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(3);
        $Client->setUrl(static::URL_DEFLATE);

        $Client->execute();

        $this->assertEquals('HTTP/1.1 200 OK', $Client->http_response_header[0]);

        $data = json_decode($Client->contents, true);

        $this->assertArrayHasKey('User-Agent', $data['headers']);
        $this->assertEquals($user_agent, $data['headers']['User-Agent']);
    }

    /**
     * @covers                   \Suzunone\CDN\Http\Client::execute()
     * @covers                   \Suzunone\CDN\Http\Client::setOpt()
     * @expectedException \ErrorException
     * @expectedExceptionMessage The requested URL returned error: 404 NOT FOUND
     */
    public function test_execute_404error()
    {
        $Client = new Client();

        $faker      = FakerFactory::create();
        $user_agent = $faker->userAgent;

        $headers = ['user-agent: ' . $user_agent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(10);
        $Client->setUrl(static::URI_404);

        $Client->execute();
    }

    /**
     * @covers                   \Suzunone\CDN\Http\Client::execute()
     * @covers                   \Suzunone\CDN\Http\Client::setOpt()
     * @expectedException \ErrorException
     * @expectedExceptionMessage The requested URL returned error: 500 INTERNAL SERVER ERROR
     */
    public function test_execute_500error()
    {
        $Client = new Client();

        $faker      = FakerFactory::create();
        $user_agent = $faker->userAgent;

        $headers = ['user-agent: ' . $user_agent];
        $Client->setHeaders($headers);
        $Client->setTimeOut(10);
        $Client->setUrl(static::URI_500);

        $Client->execute();
    }
}
