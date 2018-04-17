<?php

namespace Tests\Suzunone\CDN;

use Faker\Factory as FakerFactory;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Main;

// use Faker\Generator as FakerGenerator;

/**
 * Class BootstrapTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */
class MainTest extends TestCase
{
    /**
     * @var array
     */
    protected $server;

    /**
     * php unit set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->server = $_SERVER;
    }

    /**
     * php unit tear down
     */
    public function tearDown()
    {
        parent::tearDown();

        $_SERVER = $this->server;
    }

    /**
     * @covers \Suzunone\CDN\Main::__construct()
     * @covers \Suzunone\CDN\Main::setHostName()
     * @covers \Suzunone\CDN\Main::getHostName()
     * @test
     */
    public function test_setHostName_and_getHostName()
    {
        $Main = new Main();
        $this->assertEmpty($Main->getHostName());

        $faker = FakerFactory::create();

        $host = $faker->domainName;

        $Main->setHostName($host);

        $this->assertEquals($host, $Main->getHostName());
    }

    /**
     * @covers \Suzunone\CDN\Main::__construct()
     * @covers \Suzunone\CDN\Main::hasAllowHostName()
     * @covers \Suzunone\CDN\Main::setAllowHostName()
     * @test
     */
    public function test_allowHostName()
    {
        $faker = FakerFactory::create();

        $Main = new Main();
        $this->assertFalse($Main->hasAllowHostName($faker->domainName));

        $host = $faker->domainName;

        $Main->setAllowHostName($host);
        $this->assertTrue($Main->hasAllowHostName($host));

        $host2 = $faker->domainName;

        $Main->setAllowHostName($host2);
        $this->assertTrue($Main->hasAllowHostName($host));
        $this->assertTrue($Main->hasAllowHostName($host2));
    }

    /**
     * @covers \Suzunone\CDN\Main::__construct()
     * @covers \Suzunone\CDN\Main::setRootPath()
     * @covers \Suzunone\CDN\Main::getRootPath()
     * @test
     */
    public function test_setRootPath_and_getRootPath()
    {
        $Main = new Main();
        $this->assertEmpty($Main->getRootPath());

        $faker = FakerFactory::create();

        $slug = $faker->slug;

        $Main->setRootPath($slug);

        $this->assertEquals($slug, $Main->getRootPath());
    }

    /**
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers              \Suzunone\CDN\Main::__construct()
     * @covers              \Suzunone\CDN\Main::execute()
     * @test
     * @throws \ErrorException
     */
    public function test_execute_success()
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

        $Main = new Main();

        ob_start();
        $Main->setAllowHostName('httpbin.org')
            ->setRootPath('/index.php')
            ->execute();
        $contents = ob_get_contents();
        ob_end_clean();

        $data = json_decode($contents, true);

        $this->assertEquals($_SERVER['HTTP_USER_AGENT'], $data['headers']['User-Agent']);
    }
}
