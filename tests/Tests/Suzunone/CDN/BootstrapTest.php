<?php

namespace Tests\Suzunone\CDN;

use PHPUnit\Framework\TestCase;
use Suzunone\CDN\Bootstrap;
use Suzunone\CDN\Main;

// use Mockery as m;
// use Faker\Factory as FakerFactory;
// use Faker\Generator as FakerGenerator;

/**
 * Class BootstrapTest
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @runInSeparateProcess
 * @codeCoverageIgnore
 */
class BootstrapTest extends TestCase
{
    /**
     * @covers \Suzunone\CDN\Bootstrap::main()
     * @runInSeparateProcess
     */
    public function test_main_success()
    {
        $Main = Bootstrap::main();

        $this->assertInstanceOf(Main::class, $Main);

        $Main2 = Bootstrap::main();

        $this->assertInstanceOf(Main::class, $Main2);

        $this->assertSame($Main, $Main2);
    }
}
