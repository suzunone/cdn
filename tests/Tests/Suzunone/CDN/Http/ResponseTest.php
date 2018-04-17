<?php
/**
 * Class ResponseTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @codeCoverageIgnore
 * @runInSeparateProcess
 */

namespace Tests\Suzunone\CDN\Http;

use Faker\Factory as FakerFactory;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Http\Client;
use Suzunone\CDN\Http\Response;

// use Faker\Generator as FakerGenerator;

/**
 * Class ResponseTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @codeCoverageIgnore
 * @runInSeparateProcess
 */
class ResponseTest extends TestCase
{
    use \Tests\Suzunone\CDN\InvokeTrait;

    /**
     * @covers \Suzunone\CDN\Http\Response::sendBody()
     */
    public function test_sendBody()
    {
        $faker    = FakerFactory::create();
        $contents = $faker->randomHtml(3, 10);

        $Client                       = new Client();
        $Client->contents             = $contents;
        $Client->http_response_header = [
            0  => 'HTTP/1.1 200 OK',
            1  => 'Server: nginx/1.6.2',
            2  => 'Date: Mon, 16 Apr 2018 05:25:44 GMT',
            3  => 'Content-Type: text/html; charset=utf-8',
            4  => 'Connection: close',
            5  => 'X-Powered-By: PHP/5.6.30-0+deb8u1',
            6  => 'Content-language: en',
            7  => 'X-Frame-Options: SAMEORIGIN',
            8  => 'Set-Cookie: LAST_LANG=ja; expires=Tue, 16-Apr-2019 05:25:44 GMT; Max-Age=31536000; path=/; ' . $faker->domainName . '',
            9  => 'Set-Cookie: COUNTRY=NA%2C118.21.117.165; expires=Mon, 23-Apr-2018 05:25:44 GMT; Max-Age=604800; path=/; domain=.' . $faker->domainName . '',
            10 => 'Link: <' . $faker->url . '>; rel=shorturl',
            11 => 'Last-Modified: Mon, 16 Apr 2018 04:20:07 GMT',
            12 => 'Vary: Accept-Encoding',
        ];

        ob_start();
        $Response = new Response();
        $Response->sendBody($Client);

        $get_contents = ob_get_contents();
        ob_end_clean();

        $this->assertSame($contents, $get_contents);
    }

    /**
     * @covers              \Suzunone\CDN\Http\Response::sendHeader()
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_sendHeader_success()
    {
        $checkerMock = m::mock('alias:\Network');
        $checkerMock
            ->shouldReceive('header')
            ->once()
            ->with('Server: nginx/1.6.2')
            ->andReturn(true);

        $Response = m::mock(
            Response::class . '[parseHeader]',
            []
        );

        $faker    = FakerFactory::create();
        $contents = $faker->randomHtml(3, 10);

        $Client                       = new Client();
        $Client->contents             = $contents;
        $Client->http_response_header = [
            0  => 'HTTP/1.1 200 OK',
            1  => 'Server: nginx/1.6.2',
            2  => 'Date: Mon, 16 Apr 2018 05:25:44 GMT',
            3  => 'Content-Type: text/html; charset=utf-8',
            4  => 'Connection: close',
            5  => 'X-Powered-By: PHP/5.6.30-0+deb8u1',
            6  => 'Content-language: en',
            7  => 'X-Frame-Options: SAMEORIGIN',
            8  => 'Set-Cookie: LAST_LANG=ja; expires=Tue, 16-Apr-2019 05:25:44 GMT; Max-Age=31536000; path=/; ' . $faker->domainName . '',
            9  => 'Set-Cookie: COUNTRY=NA%2C118.21.117.165; expires=Mon, 23-Apr-2018 05:25:44 GMT; Max-Age=604800; path=/; domain=.' . $faker->domainName . '',
            10 => 'Link: <' . $faker->url . '>; rel=shorturl',
            11 => 'Last-Modified: Mon, 16 Apr 2018 04:20:07 GMT',
            12 => 'Vary: Accept-Encoding',
        ];

        $Response
            // 1. Protectedメソッドをモック
            ->shouldAllowMockingProtectedMethods()
            // 2. モック開始
            ->shouldReceive('parseHeader')
            // 3. 実行回数1
            ->once()
            // 4. 引数
            ->with($Client->http_response_header)
            // 5. 返り値
            ->andReturn(['Server: nginx/1.6.2']);

        /**
         * @var Response $Response
         */
        $Response->sendHeader($Client);
    }

    /**
     * @covers \Suzunone\CDN\Http\Response::parseHeader()
     * @throws \ReflectionException
     */
    public function test_parseHeader_simple()
    {
        $faker = FakerFactory::create();

        $url = $faker->url;

        $host = $faker->domainName;

        $http_response_header = [
            0  => 'HTTP/1.1 200 OK',
            1  => 'Server: nginx/1.6.2',
            2  => 'Date: Mon, 16 Apr 2018 05:25:44 GMT',
            3  => 'Content-Type: text/html; charset=utf-8',
            4  => 'Connection: close',
            5  => 'X-Powered-By: PHP/5.6.30-0+deb8u1',
            6  => 'Content-language: en',
            7  => 'X-Frame-Options: SAMEORIGIN',
            8  => 'Set-Cookie: LAST_LANG=ja; expires=Tue, 16-Apr-2019 05:25:44 GMT; Max-Age=31536000; path=/; ' . $host . '',
            9  => 'Set-Cookie: COUNTRY=NA%2C118.21.117.165; expires=Mon, 23-Apr-2018 05:25:44 GMT; Max-Age=604800; path=/; domain=.' . $host . '',
            10 => 'Link: <' . $url . '>; rel=shorturl',
            11 => 'Last-Modified: Mon, 16 Apr 2018 04:20:07 GMT',
            12 => 'Vary: Accept-Encoding',
        ];

        $Response = new Response();

        $headers_g = $this->invokeExecuteMethod($Response, 'parseHeader', [$http_response_header]);

        $headers = [];
        foreach ($headers_g as $header) {
            $headers[] = $header;
        }

        $this->assertSame(
            [
                0  => 'Server: nginx/1.6.2',
                1  => 'Date: Mon, 16 Apr 2018 05:25:44 GMT',
                2  => 'Content-Type: text/html; charset=utf-8',
                3  => 'Connection: close',
                4  => 'X-Powered-By: PHP/5.6.30-0+deb8u1',
                5  => 'Content-language: en',
                6  => 'X-Frame-Options: SAMEORIGIN',
                7  => 'Set-Cookie: LAST_LANG=ja; expires=Tue, 16-Apr-2019 05:25:44 GMT; Max-Age=31536000; path=/; ' . $host . '',
                8  => 'Set-Cookie: COUNTRY=NA%2C118.21.117.165; expires=Mon, 23-Apr-2018 05:25:44 GMT; Max-Age=604800; path=/; domain=.' . $host . '',
                9  => 'Link: <' . $url . '>; rel=shorturl',
                10 => 'Last-Modified: Mon, 16 Apr 2018 04:20:07 GMT',
                11 => 'Vary: Accept-Encoding',
            ],

            $headers
        );
    }

    /**
     * @covers \Suzunone\CDN\Http\Response::parseHeader()
     * @throws \ReflectionException
     */
    public function test_parseHeader_redirected()
    {
        $faker = FakerFactory::create();

        $url1 = $faker->url;
        $url2 = $faker->url;

        $http_response_header = [
            0  => 'HTTP/1.1 301 Moved Permanently',
            1  => 'Date: Mon, 16 Apr 2018 05:25:59 GMT',
            2  => 'Server: Apache',
            3  => 'Location: ' . $url1,
            4  => 'Vary: Accept-Encoding',
            5  => 'Content-Length: 301',
            6  => 'Connection: close',
            7  => 'Content-Type: text/html; charset=iso-8859-1',
            8  => 'HTTP/1.1 302 Found',
            9  => 'Date: Mon, 16 Apr 2018 05:26:00 GMT',
            10 => 'Server: Apache',
            11 => 'X-Powered-By: PHP/5.6.22',
            12 => 'location: ' . $url2,
            13 => 'Vary: Accept-Encoding',
            14 => 'Content-Length: 0',
            15 => 'Connection: close',
            16 => 'Content-Type: text/html; charset=UTF-8',
            17 => 'HTTP/1.1 200 OK',
            18 => 'Date: Mon, 16 Apr 2018 05:26:00 GMT',
            19 => 'Server: Apache',
            20 => 'Last-Modified: Sat, 13 Aug 2016 13:15:56 GMT',
            21 => 'ETag: "19da-539f3cc997b00"',
            22 => 'Accept-Ranges: bytes',
            23 => 'Content-Length: 6618',
            24 => 'Vary: Accept-Encoding',
            25 => 'Connection: close',
            26 => 'Content-Type: text/html; charset=UTF-8',
            27 => 'Pragma: no-cache',
        ];

        $Response = new Response();

        $headers_g = $this->invokeExecuteMethod($Response, 'parseHeader', [$http_response_header]);

        $headers = [];
        foreach ($headers_g as $header) {
            $headers[] = $header;
        }

        $this->assertSame(
            [
                0 => 'Date: Mon, 16 Apr 2018 05:26:00 GMT',
                1 => 'Server: Apache',
                2 => 'Last-Modified: Sat, 13 Aug 2016 13:15:56 GMT',
                3 => 'ETag: "19da-539f3cc997b00"',
                4 => 'Accept-Ranges: bytes',
                5 => 'Content-Length: 6618',
                6 => 'Vary: Accept-Encoding',
                7 => 'Connection: close',
                8 => 'Content-Type: text/html; charset=UTF-8',
            ],

            $headers
        );
    }
}
