<?php

namespace App\Tests\Entity\IP\v6;

use App\Entity\IP\v6\Ipv6subnet;
use PHPUnit\Framework\TestCase;

class Ipv6subnetTest extends TestCase
{
    public function testRegularSubnet(): void
    {
        $ipv6Subnet = new Ipv6subnet('2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/64');
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6Address
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:0:0:0:0',
            $ipv6Subnet->ipv6NetworkAddress->getHexa()
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:ffff:ffff:ffff:ffff',
            $ipv6Subnet->ipv6LastAddress->getHexa()
        );
        $this->assertEquals(
            'ffff:ffff:ffff:ffff:0:0:0:0',
            $ipv6Subnet->ipv6Netmask->getHexa()
        );
    }

    public function testSmallSubnet(): void
    {
        $ipv6Subnet = new Ipv6subnet('2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/126');
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6Address
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f4',
            $ipv6Subnet->ipv6NetworkAddress->getHexa()
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6LastAddress->getHexa()
        );
        $this->assertEquals(
            'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc',
            $ipv6Subnet->ipv6Netmask->getHexa()
        );
    }

    public function testHost(): void
    {
        $ipv6Subnet = new Ipv6subnet('2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/128');
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6Address
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6NetworkAddress->getHexa()
        );
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6LastAddress->getHexa()
        );
        $this->assertEquals(
            'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff',
            $ipv6Subnet->ipv6Netmask->getHexa()
        );
    }

    public function testFullRange(): void
    {
        $ipv6Subnet = new Ipv6subnet('2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/1');
        $this->assertEquals(
            '2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7',
            $ipv6Subnet->ipv6Address
        );
        $this->assertEquals(
            '0:0:0:0:0:0:0:0',
            $ipv6Subnet->ipv6NetworkAddress->getHexa()
        );
        $this->assertEquals(
            '7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff',
            $ipv6Subnet->ipv6LastAddress->getHexa()
        );
        $this->assertEquals(
            '8000:0:0:0:0:0:0:0',
            $ipv6Subnet->ipv6Netmask->getHexa()
        );
    }
}
