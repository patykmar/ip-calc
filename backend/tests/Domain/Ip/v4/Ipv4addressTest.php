<?php
declare(strict_types=1);

namespace App\Tests\Domain\IP\v4;

use App\Domain\Ip\v4\Ipv4address;
use PHPUnit\Framework\TestCase;

final class Ipv4addressTest extends TestCase
{
    public function testIpAAddress()
    {
        // default value is 10.0.0.1
        $ipV4Address = new Ipv4address();
        $this->assertEquals("10.0.0.1", $ipV4Address->getDecadic());
        $this->assertEquals(167772161, $ipV4Address->getInteger());
        $this->assertEquals("00001010000000000000000000000001", $ipV4Address->getBinary());
        $this->assertEquals(32, strlen($ipV4Address->getBinary()));
        $this->assertCount(4, $ipV4Address->getAddressArray());
        $this->assertEquals(10, $ipV4Address->getAddressArray()[0]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[1]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[2]);
        $this->assertEquals(1, $ipV4Address->getAddressArray()[3]);
    }

    public function testIpAAddressWithParameter()
    {
        $ipV4Address = new Ipv4address("10.0.0.0");
        $this->assertEquals("10.0.0.0", $ipV4Address->getDecadic());
        $this->assertEquals(167772160, $ipV4Address->getInteger());
        $this->assertEquals("00001010000000000000000000000000", $ipV4Address->getBinary());
        $this->assertEquals(32, strlen($ipV4Address->getBinary()));
        $this->assertCount(4, $ipV4Address->getAddressArray());
        $this->assertEquals(10, $ipV4Address->getAddressArray()[0]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[1]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[2]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[3]);
    }

    public function testIpBAddress()
    {
        $ipV4Address = new Ipv4address("172.16.0.0");
        $this->assertEquals("172.16.0.0", $ipV4Address->getDecadic());
        $this->assertEquals(2886729728, $ipV4Address->getInteger());
        $this->assertEquals("10101100000100000000000000000000", $ipV4Address->getBinary());
        $this->assertEquals(32, strlen($ipV4Address->getBinary()));
        $this->assertCount(4, $ipV4Address->getAddressArray());
        $this->assertEquals(172, $ipV4Address->getAddressArray()[0]);
        $this->assertEquals(16, $ipV4Address->getAddressArray()[1]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[2]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[3]);
    }

    public function testIpCAddress()
    {
        $ipV4Address = new Ipv4address("192.168.0.0");
        $this->assertEquals("192.168.0.0", $ipV4Address->getDecadic());
        $this->assertEquals(3232235520, $ipV4Address->getInteger());
        $this->assertEquals("11000000101010000000000000000000", $ipV4Address->getBinary());
        $this->assertEquals(32, strlen($ipV4Address->getBinary()));
        $this->assertCount(4, $ipV4Address->getAddressArray());
        $this->assertEquals(192, $ipV4Address->getAddressArray()[0]);
        $this->assertEquals(168, $ipV4Address->getAddressArray()[1]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[2]);
        $this->assertEquals(0, $ipV4Address->getAddressArray()[3]);
    }

//    public function testException()
//    {
//        $this->assertContainsOnlyInstancesOf(InvalidArgumentException::class, new Ipv4address("192.400.0.0"));
//    }
}
