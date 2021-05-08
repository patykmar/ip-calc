<?php

namespace App\Entity\IP\v6;

use InvalidArgumentException;

/**
 * Description of Ipv6address
 *
 * @author patykmar
 */
class Ipv6address
{

   /** @var int binary length of IPv6 address */
   public const IPV6_BIN_LEN = 128;

   /** @var int number of bits */
   public const HEXTET_BIN_LEN = 16;

   /** @var int total number of hextet in IPv6 address */
   public const HEXTET_COUNT = 8;

   /** @var string IPv6 address in hexa format */
   private $hexa;

   /** @var string IPv6 address in binary format */
   private $bin;

   /** @var array() IPv6 each hextet in dedicate item of one array */
   private $ipv6Array;

   public function __construct(string $ipv6address = "::1")
   {
      if (filter_var($ipv6address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
         $this->setHexa(strtolower($ipv6address));

         $this->hexToArray();

         $this->hexToBin();
      } else {
         throw new InvalidArgumentException("IPv6 is not in valid format");
      }

      if (strlen($this->bin) != self::IPV6_BIN_LEN)
         throw new InvalidArgumentException("Len of IPv6 is out of expected range");
   }

   protected function setHexa(string $ipv6Hexa): void
   {
      $this->hexa = $ipv6Hexa;
   }

   /**
    * Convert IPv6 address to array splitted by colon
    */
   private function hexToArray(): void
   {
      // split ipv6 address to array 
      $ipv6Array = explode(':', $this->hexa);

      // is the IPv6 in shortened representation 
      if (str_contains($this->hexa, "::")) {
         // then add missing zeros 

         $ipv6ArrayAddMissingZeros = array();
         $countOfMissingHextet = self::HEXTET_COUNT - count($ipv6Array);

         foreach ($ipv6Array as $hextet) {
            if (strlen($hextet) == 0) {
               for ($i = 0; $i <= $countOfMissingHextet; $i++)
                  $ipv6ArrayAddMissingZeros[] = "0000";

               // already added missing zeros in to IPv6 adderr, set variable to zero
               $countOfMissingHextet = 0;
            } else {
               $ipv6ArrayAddMissingZeros[] = $hextet;
            }
#            if (count($ipv6Array) > self::HEXTET_COUNT)
#               break;
         }

         // set the original array with added zeros
         $this->ipv6Array = $ipv6ArrayAddMissingZeros;
      } else {
         $this->ipv6Array = $ipv6Array;
      }

      // unset local variable
      unset($ipv6ArrayAddMissingZeros, $ipv6Array);
   }

   /**
    * Convert IPv6 address from Hexa to Binary format. Only for internal purpose
    * @return void 
    */
   private function hexToBin(): void
   {
      $localBin = "";
      foreach ($this->ipv6Array as $hextet) {
         $localBin .= str_pad(base_convert($hextet, 16, 2), self::HEXTET_BIN_LEN, "0", STR_PAD_LEFT);
      }
      $this->setBin($localBin);
   }

   /**
    * @return string IPv6 address in Hexa format 
    */
   public function getHexa(): string
   {
      return $this->hexa;
   }

   /**
    * @return string IPv6 address in Binary format
    */
   public function getBin(): string
   {
      return $this->bin;
   }

   /**
    * Convert IP from BIN to HEXA
    * BIN=11000000101010000000000100000001 to 192.168.1.1
    * @param string $inputIP IPv4Address in binary format
    * @return Ipv6address New object of Ipv4address
    */
   public static function binToDec(string $inputIP): Ipv6address
   {
      // input must be only in binary format and length must be 128 bits
      if (filter_var($inputIP, FILTER_VALIDATE_REGEXP,
                      array(
                          "options" => array(
                              "regexp" => '/[0,1]/'
                  )))
              AND
              filter_var(strlen($inputIP), FILTER_VALIDATE_INT, array(
                  'options' => array(
                      'min_range' => self::IPV6_BIN_LEN,
                      'max_range' => self::IPV6_BIN_LEN
          )))) {

         $inputIpArray = str_split($inputIP, self::HEXTET_BIN_LEN);
         $ipOutput = "";
         foreach ($inputIpArray as $hextet){
            $ipOutput .= base_convert($hextet, 2, 16). ":";
         }

         #dump($ipOutput);
         #dump(substr($ipOutput, 0, -1));
         #exit;
         return new Ipv6address(substr($ipOutput, 0, -1));
      } else {
         throw new InvalidArgumentException("Input is not in valid format");
      }
   }

   /**
    * @param string $binary binary format of IPv6 address
    */
   protected function setBin(string $binary): void
   {
      $this->bin = $binary;
   }

   protected function setIpv6Array(array $ipv6Array): void
   {
      $this->ipv6Array = $ipv6Array;
   }

   /**
    * @return string Hexa representation of IPv6 address
    */
   public function __toString(): string
   {
      return $this->getHexa();
   }

}
