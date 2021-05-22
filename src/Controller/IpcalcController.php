<?php

namespace App\Controller;

use App\Entity\IP\v4\Ipv4subnet;
use App\Entity\IP\v6\Ipv6subnet;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IpcalcController extends AbstractController
{
    private LoggerInterface $logger;

    private const ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    /**
     * IpcalcController constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @Route("ipv4calc/{ip}", name="ipv4calc", requirements={"ip"=".+"}, defaults={"192.168.1.0/24"})
     * @param string $ip
     * @return Response
     */
    public function versionFourCalc(string $ip = "192.168.1.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        return $this->render('ipcalc/ipv4.html.twig', [
            'subnet' => $ipv4Subnet,
        ]);
    }

    /**
     * @Route("api/ipv4calc/{ip}", name="apiIpv4calc", requirements={"ip"=".+"}, defaults={"192.168.1.0/24"})
     * @param string $ip
     * @return Response
     */
    public function apiVersionFourCalc(string $ip = "192.168.1.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        $jsonResponse = $this->json([
            'network-subnet' => [
                'key' => 'Network subnet:',
                'value' => $ipv4Subnet->ipv4AddressNetwork->getDecadic() . "/" . $ipv4Subnet->ipv4Netmask->getCidr()
            ],
            'netmask' => [
                'key' => 'Netmask:',
                'value' => $ipv4Subnet->ipv4Netmask->getDecadic()
            ],
            'network-address' => [
                'key' => 'Network address:',
                'value' => $ipv4Subnet->ipv4AddressNetwork->getDecadic()
            ],
            'first-address' => [
                'key' => 'First address:',
                'value' => $ipv4Subnet->ipv4FirstAddress->getDecadic()
            ],
            'last-address' => [
                'key' => 'Last address:',
                'value' => $ipv4Subnet->ipv4LastAddress->getDecadic()
            ],
            'broadcast-address' => [
                'key' => 'Broadcast address:',
                'value' => $ipv4Subnet->ipv4AddressBroadcast->getDecadic()
            ],
            'number-of-usable-address' => [
                'key' => 'Number of usable address:',
                'value' => $ipv4Subnet->ipv4LastAddress->getInteger() - $ipv4Subnet->ipv4FirstAddress->getInteger() + 1
            ],
            'nsx-cidr' => [
                'key' => 'NSX CIDR:',
                'value' => $ipv4Subnet->ipv4FirstAddress->getDecadic() . '/' . $ipv4Subnet->ipv4Netmask->getCidr()
            ],
            'nsx-static-ip-pool' => [
                'key' => 'NSX Static IP pool:',
                'value' => $ipv4Subnet->ipv4SecondAddress->getDecadic() . '-' . $ipv4Subnet->ipv4LastAddress->getDecadic()
            ],
        ]);

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }

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
            'lookup-address' => $ipv6Subnet->ipv6Address->getHexa() . "/" . $ipv6Subnet->ipv6Netmask->getCidr(),
            'network-subnet' => $ipv6Subnet->ipv6NetworkAddress->getHexa() . '/' . $ipv6Subnet->ipv6Netmask->getCidr(),
            'netmask' => $ipv6Subnet->ipv6Netmask->getHexa(),
            'network-address' => $ipv6Subnet->ipv6NetworkAddress->getHexa(),
            'last-address' => $ipv6Subnet->ipv6LastAddress->getHexa(),
        ]);

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }
}
