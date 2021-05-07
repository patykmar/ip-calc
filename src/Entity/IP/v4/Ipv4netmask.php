<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\IP\v4;

use App\Classes\IP\Model\Ipv4NetworkModel;
use InvalidArgumentException;

/**
 * Description of Ipv4netmask
 *
 * @author Martin Patyk
 */
class Ipv4netmask extends Ipv4address
{
    public const CIDR_RANGE_MIN = 1;

    public const CIDR_RANGE_MAX = 32;

   /** @var int network mask in CIDR notation */
   private $cidr;

   /** @var string Wildcard mask */
   private $wildcard;

   /** @var int Wildcard mask integer */
   private $wildcardInt;

   public function __construct(int $cidr = 24)
   {
      if (filter_var($cidr, FILTER_VALIDATE_INT,
                      ['options' => array(
                              'min_range' => self::CIDR_RANGE_MIN,
                              'max_range' => self::CIDR_RANGE_MAX,
                  )])) {
         $this->setCidr($cidr);
         parent::__construct($this->getDecadic());
         $this->setWildcard();
         $this->setWildcardInt();
      } else {
         throw new InvalidArgumentException("Netmask is in wrong range");
      }
   }

   /** @param int $cidr CIDR format of netmask 1 - 32 */
   private function setCidr(int $cidr): void
   {
      $this->cidr = $cidr;
   }

   /** Set network wildcard based on network binary format */
   private function setWildcard(): void
   {
      $out_local = "";
      $netMaskBin_local = $this->getBinary();
      for ($i = 0; $i < strlen($netMaskBin_local); $i++) {
         if ($netMaskBin_local[$i] == '0') {
            $out_local .= '1';
         } else {
            $out_local .= '0';
         }
      }
      $this->wildcard = $out_local;
   }

   /** Set network wildcard as integer number */
   private function setWildcardInt(): void
   {
      $this->wildcardInt = bindec($this->getWildcard());
   }

    /**
    * @return int network mask in CIRD format
    */
   public function getCidr(): int
   {
      return $this->cidr;
   }

   /** @return string network mask in dec format  */
   public function getDecadic(): string
   {
      return Ipv4NetworkModel::CIDR_TO_NETWORK[$this->getCidr()];
   }

   /** @return string network wildcard in binary */
   public function getWildcard(): string
   {
      return $this->wildcard;
   }

   /** @return int number of wildcard mask */
   public function getWildcardInt(): int
   {
      return $this->wildcardInt;
   }

}
