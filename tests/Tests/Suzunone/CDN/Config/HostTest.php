<?php
/**
 * Class HostTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */

namespace Tests\Suzunone\CDN\Config;

use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Config\Host;

/**
 * Class HostTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */
class HostTest extends TestCase
{
    use \Tests\Suzunone\CDN\InvokeTrait;

    /**
     * @covers \Suzunone\CDN\Config\Host::setRequestTimeOut()
     * @throws \ReflectionException
     */
    public function test_setRequestTimeOut()
    {
        $host = new Host();

        $host->setRequestTimeOut(10);

        $this->assertEquals(
            $this->invokeGetProperty($host, 'request_time_out'),
            10
        );

        return $host;
    }

    /**
     * @depends test_setRequestTimeOut
     * @covers  \Suzunone\CDN\Config\Host::getRequestTimeOut()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getRequestTimeOut(Host $host)
    {
        $this->assertEquals($host->getRequestTimeOut(), 10);
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setForwardHeadersWhiteList()
     * @throws \ReflectionException
     */
    public function test_setForwardHeadersWhiteList()
    {
        $host = new Host();

        $host->setForwardHeadersWhiteList('user-agent');

        $this->assertEquals(
            $this->invokeGetProperty($host, 'forward_headers_white_list'),
            ['user-agent' => 'user-agent']
        );

        return $host;
    }

    /**
     * @depends test_setForwardHeadersWhiteList
     * @covers  \Suzunone\CDN\Config\Host::getForwardHeadersWhiteList()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getForwardHeadersWhiteList(Host $host)
    {
        $this->assertEquals(
            $host->getForwardHeadersWhiteList(),
            ['user-agent' => 'user-agent']
        );
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setForwardHeader()
     * @throws \ReflectionException
     */
    public function test_setForwardHeader()
    {
        $host = new Host();

        $host->setForwardHeader(Host::FORWARD_WHITE_LIST);
        $this->assertEquals(
            $this->invokeGetProperty($host, 'forward_header'),
            Host::FORWARD_WHITE_LIST
        );

        return $host;
    }

    /**
     * @depends test_setForwardHeader
     * @covers  \Suzunone\CDN\Config\Host::getForwardHeader()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getForwardHeader(Host $host)
    {
        $this->assertEquals(
            $host->getForwardHeader(),
            Host::FORWARD_WHITE_LIST
        );
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setHttpPort()
     * @throws \ReflectionException
     */
    public function test_setHttpPort()
    {
        $host = new Host();

        $host->setHttpPort('8080');

        $this->assertEquals(
            $this->invokeGetProperty($host, 'http_port'),
            '8080'
        );

        return $host;
    }

    /**
     * @depends test_setHttpPort
     * @covers  \Suzunone\CDN\Config\Host::getHttpPort()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getHttpPort(Host $host)
    {
        $this->assertEquals($host->getHttpPort(), '8080');
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setHttpsPort()
     * @throws \ReflectionException
     */
    public function test_setHttpsPort()
    {
        $host = new Host();

        $host->setHttpsPort(443443);

        $this->assertEquals(
            $this->invokeGetProperty($host, 'https_port'),
            443443
        );

        return $host;
    }

    /**
     * @depends test_setHttpsPort
     * @covers  \Suzunone\CDN\Config\Host::getHttpsPort()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getHttpsPort(Host $host)
    {
        $this->assertEquals($host->getHttpsPort(), '443443');
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setCustomOriginHeader()
     */
    public function test_setCustomOriginHeader()
    {
        $host = new Host();

        $host->setCustomOriginHeader('user-agent', 'wget');

        return $host;
    }

    /**
     * @depends test_setCustomOriginHeader
     * @covers  \Suzunone\CDN\Config\Host::getCustomOriginHeaders()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getCustomOriginHeaders(Host $host)
    {
        $this->assertEquals(['user-agent' => 'user-agent: wget'], $host->getCustomOriginHeaders());
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setSSLVerifyPeer()
     * @throws \ReflectionException
     */
    public function test_setSSLVerifyPeer()
    {
        $host = new Host();

        $host->setSSLVerifyPeer(true);

        $this->assertTrue(
            $this->invokeGetProperty($host, 'ssl_verify_peer')
        );

        return $host;
    }

    /**
     * @depends test_setSSLVerifyPeer
     * @covers  \Suzunone\CDN\Config\Host::getSSLVerifyPeer()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getSSLVerifyPeer(Host $host)
    {
        $this->assertTrue($host->getSSLVerifyPeer());
    }

    /**
     * @covers \Suzunone\CDN\Config\Host::setOriginProtocolPolicy()
     * @throws \ReflectionException
     */
    public function test_setOriginProtocolPolicy()
    {
        $host = new Host();

        $host->setOriginProtocolPolicy(Host::HTTPS_ONLY);
        $this->assertEquals(
            $this->invokeGetProperty($host, 'origin_protocol_policy'),
            Host::HTTPS_ONLY
        );

        return $host;
    }

    /**
     * @depends test_setOriginProtocolPolicy
     * @covers  \Suzunone\CDN\Config\Host::getOriginProtocolPolicy()
     * @param \Suzunone\CDN\Config\Host $host
     */
    public function test_getOriginProtocolPolicy(Host $host)
    {
        $this->assertEquals(Host::HTTPS_ONLY, $host->getOriginProtocolPolicy());
    }
}
