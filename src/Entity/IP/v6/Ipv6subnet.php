<?php

namespace App\IpamModule\Classes;

/**
 * Description of Ipv6subnet
 *
 * @author Martin Patyk  - 2021/03/04
 */
class Ipv6subnet
{

   /** @var \App\IpamModule\Classes\Ipv6address IPv6 address object */
   public Ipv6address $ipv6Address;

   /** @var \App\IpamModule\Classes\Ipv6address IPv6 network address object */
   public Ipv6address $ipv6NetworkAddress;

   /** @var \App\IpamModule\Classes\Ipv6address IPv6 first address in subnet */
   public Ipv6address $ipv6FirstAddress;

   /** @var \App\IpamModule\Classes\Ipv6address IPv6 last address in subnet */
   public Ipv6address $ipv6LastAddress;

   /** @var \App\IpamModule\Classes\Ipv6address IPv6 network object */
   public Ipv6netmask $ipv6Netmask;

   /**
    * @param string $subnet IPv6 subnet eg. 2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/64
    */
   public function __construct(string $subnet)
   {
      // $ipv6AddressAndMask[0] -> \App\Classes\Ipv6address
      // $ipv6AddressAndMask[1] -> \App\Classes\Ipv6netmask
      $ipv6AddressAndMask = Utils\Strings::split(Utils\Strings::trim($subnet), "~/~");
      if (count($ipv6AddressAndMask) == 2) {
         try
         {
            #dump($ipv6AddressAndMask);
            $this->setIpv6Address(new Ipv6address($ipv6AddressAndMask[0]));
            $this->setIpv6Netmask(new Ipv6netmask($ipv6AddressAndMask[1]));

            $this->computeNetworkAddress();
            $this->computeLastAddress();
         } catch (\Nette\InvalidArgumentException $exc)
         {
            throw new \Nette\InvalidArgumentException($exc->getMessage());
         }
      } else {
         throw new \Nette\InvalidArgumentException("Invalid input parametr");
      }
   }

   /**
    * Compute network address based on address and network mask
    */
   private function computeNetworkAddress(): void
   {
      $addressBinary_local = $this->getIpv6Address()->getBin();
      $netmaskCidr_local = $this->ipv6Netmask->getCidr();
      $addressNetworkBin_local = "";


      for ($i = 0; $i < strlen($addressBinary_local); $i++):
         if ($i < $netmaskCidr_local):
            $addressNetworkBin_local .= $addressBinary_local[$i];
         else:
            $addressNetworkBin_local .= "0";
         endif;
      endfor;


      $this->setIpv6NetworkAddress(Ipv6address::binToDec($addressNetworkBin_local));
      unset($addressBinary_local, $netmaskCidr_local, $addressNetworkBin_local);
   }

   /**
    * Compute last address of subnet
    */
   private function computeLastAddress(): void
   {
      $addressBinary_local = $this->getIpv6Address()->getBin();
      $netmaskCidr_local = $this->ipv6Netmask->getCidr();
      $addressNetworkBin_local = "";


      for ($i = 0; $i < strlen($addressBinary_local); $i++):
         if ($i < $netmaskCidr_local):
            $addressNetworkBin_local .= $addressBinary_local[$i];
         else:
            $addressNetworkBin_local .= "1";
         endif;
      endfor;


      $this->setIpv6LastAddress(Ipv6address::binToDec($addressNetworkBin_local));
      unset($addressBinary_local, $netmaskCidr_local, $addressNetworkBin_local);
   }

   /**
    * @return Ipv6address Object with IPv6 address
    */
   public function getIpv6Address(): Ipv6address
   {
      return $this->ipv6Address;
   }

   /**
    * @return Ipv6netmask Object with IPv6 netmask
    */
   public function getIpv6Netmask(): Ipv6netmask
   {
      return $this->ipv4Netmask;
   }

   /**
    * @param \App\IpamModule\Classes\Ipv6address $ipv6Address set object with IPv6 address
    */
   private function setIpv6Address(Ipv6address $ipv6Address): void
   {
      $this->ipv6Address = $ipv6Address;
   }

   /**
    * @param \App\IpamModule\Classes\Ipv6netmask $ipv6Netmask set object with IPv6 netmask
    */
   private function setIpv6Netmask(Ipv6netmask $ipv6Netmask): void
   {
      $this->ipv6Netmask = $ipv6Netmask;
   }

   /**
    * @param \App\IpamModule\Classes\Ipv6address $ipv6NetworkAddress set object with IPv6 network address
    */
   private function setIpv6NetworkAddress(Ipv6address $ipv6NetworkAddress): void
   {
      $this->ipv6NetworkAddress = $ipv6NetworkAddress;
   }

   /**
    * @param \App\IpamModule\Classes\Ipv6address $ipv6LastAddress set object with IPv6 last address in this subnet
    */
   private function setIpv6LastAddress(Ipv6address $ipv6LastAddress): void
   {
      $this->ipv6LastAddress = $ipv6LastAddress;
   }

}