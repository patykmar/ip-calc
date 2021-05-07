<?php

namespace App\Controller;

use App\Entity\IP\v4\Ipv4subnet;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IpcalcController extends AbstractController
{
    private LoggerInterface $logger;

    /**
     * IpcalcController constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @Route("/ipv4calc/{ip}", name="ipv4calc", requirements={"ip"=".+"}, defaults={"192.168.1.0/24"})
     * @param string $ip
     * @return Response
     */
    public function versionFourCalc(string $ip = "192.168.1.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv4Subnet = "192.168.2.0/24";
        }


        return $this->render('ipcalc/index.html.twig', [
            'subnet' => $ipv4Subnet,
        ]);
    }

    /**
     * @Route("/ipv6calc/{ip}", name="ipv6calc", requirements={"ip"=".+"}, defaults={"192.168.1.0/24"})
     * @param string $ip
     * @return Response
     */
    public function versionSixCalc(string $ip = "abc::/64")
    {

        return $this->render('ipcalc/index.html.twig', [
            'subnet' => $ip,
        ]);
    }
}
