<?php

namespace App\Tests\Services;

use App\Domain\Ip\v6\Ipv6subnet;
use App\Services\Ipv6subnetService;
use PHPUnit\Framework\TestCase;

class Ipv6subnetServiceTest extends TestCase
{

    public function testPrepareJsonResponse()
    {
        $ipv6subnet = new Ipv6subnet('2a01:4240:5f52:8a84:6856:5728:3ae:c4f8/64');

        $ipv6subnetService = new Ipv6subnetService();
        $response = $ipv6subnetService->prepareJsonResponse($ipv6subnet);

        $this->assertCount(5, $response);

        $this->assertEquals('Lookup address:', $response['lookup-address']['key']);
        $this->assertEquals('2a01:4240:5f52:8a84:6856:5728:3ae:c4f8/64', $response['lookup-address']['value']);

        $this->assertEquals('Netmask:', $response['netmask']['key']);
        $this->assertEquals('ffff:ffff:ffff:ffff:0:0:0:0', $response['netmask']['value']);

        $this->assertEquals('2a01:4240:5f52:8a84:0:0:0:0', $response['network-address']['value']);

        $this->assertEquals("2a01:4240:5f52:8a84:ffff:ffff:ffff:ffff", $response['last-address']['value']);
    }
}
