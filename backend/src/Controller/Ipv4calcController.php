<?php

namespace App\Controller;

use App\Entity\IP\v4\Ipv4subnet;
use App\Services\Ipv4subnetService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Ipv4calcController extends AbstractController
{
    /** @var int how many smaller subnets will be calculate */
    private const DEEP_INDEX = 4;

    /**
     * @var Ipv4subnetService
     */
    private Ipv4subnetService $ipv4subnetService;

    private const ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    /**
     * IpcalcController constructor.
     * @param Ipv4subnetService $ipv4subnetService
     */
    public function __construct(Ipv4subnetService $ipv4subnetService)
    {
        $this->ipv4subnetService = $ipv4subnetService;
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
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        $subnetsArray = $this->ipv4subnetService->getSmallerSubnet(
            $ipv4Subnet,
            $ipv4Subnet->ipv4Netmask->getCidr(),
            self::DEEP_INDEX
        );

        return $this->render('ipcalc/ipv4.html.twig', [
            'subnet' => $ipv4Subnet,
            'smallerSubnets' => $subnetsArray,
        ]);
    }

    /**
     * @Route("api/ipv4calc/{ip}", name="apiIpv4calc", requirements={"ip"=".+"}, defaults={"192.168.1.0/24"})
     * @param string $ip
     * @return Response
     */
    public function apiVersionFourCalc(string $ip = "172.10.20.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        $smallerSubnets = $this->ipv4subnetService->getSmallerSubnet(
            $ipv4Subnet,
            $ipv4Subnet->ipv4Netmask->getCidr(),
            self::DEEP_INDEX
        );

        $jsonResponse = $this->json(
            $this->ipv4subnetService->prepareJsonResponse(
                $ipv4Subnet,
                $smallerSubnets
            ));

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }


}
