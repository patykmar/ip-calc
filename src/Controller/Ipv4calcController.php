<?php

namespace App\Controller;

use App\Entity\IP\v4\Ipv4subnet;
use App\Services\Ipv4subnetService;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Ipv4calcController extends AbstractController
{
    private LoggerInterface $logger;

    private Ipv4subnetService $ipv4subnetService;

    private const ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    /**
     * IpcalcController constructor.
     * @param LoggerInterface $logger
     * @param Ipv4subnetService $ipv4subnetService
     */
    public function __construct(LoggerInterface $logger, Ipv4subnetService $ipv4subnetService)
    {
        $this->logger = $logger;
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
    public function apiVersionFourCalc(string $ip = "172.20.30.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        dump($this->ipv4subnetService->prepareJsonResponse($ipv4Subnet));

        $jsonResponse = $this->json(
            $this->ipv4subnetService->prepareJsonResponse($ipv4Subnet)
        );

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }


}
