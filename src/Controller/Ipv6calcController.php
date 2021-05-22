<?php

namespace App\Controller;

use App\Entity\IP\v6\Ipv6subnet;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ipv6calcController extends AbstractController
{

    private const ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    /**
     * @Route("/ipv6calc/{ip}", name="ipv6calc", requirements={"ip"=".+"}, defaults={"abc::/64"})
     * @param string $ip
     * @return Response
     */
    public function versionSixCalc(string $ip = "abc::/64")
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv6Subnet = new Ipv6subnet("abc::/64");
        }

        return $this->render('ipcalc/ipv6.html.twig', [
            'subnet' => $ipv6Subnet,
        ]);
    }

    /**
     * @Route("/api/ipv6calc/{ip}", name="apiIpv6calc", requirements={"ip"=".+"}, defaults={"abc::/64"})
     * @param string $ip
     * @return Response
     */
    public function apiVersionSixCalc(string $ip = "abc::/64")
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv6Subnet = new Ipv6subnet("abc::/64");
        }

        $jsonResponse = $this->json([
            'lookup-address' => [
                'key' => 'Lookup address:',
                'value' => $ipv6Subnet->ipv6Address->getHexa() . "/" . $ipv6Subnet->ipv6Netmask->getCidr()
            ],
            'network-subnet' => [
                'key' => 'Network subnet:',
                'value' => $ipv6Subnet->ipv6NetworkAddress->getHexa() . '/' . $ipv6Subnet->ipv6Netmask->getCidr()
            ],
            'netmask' => [
                'key' => 'Netmask:',
                'value' => $ipv6Subnet->ipv6Netmask->getHexa()
            ],
            'network-address' => [
                'key' => 'Network address:',
                'value' => $ipv6Subnet->ipv6NetworkAddress->getHexa()
            ],
            'last-address' => [
                'key' => 'Last address:',
                'value' => $ipv6Subnet->ipv6LastAddress->getHexa()
            ],
        ]);

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }
}
