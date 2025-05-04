<?php

namespace App\Domain\Ip;

interface IpAddress
{
    /** @return string Binary format address eg. 11000000101010000000000100000001 */
    function getBinary(): string;

    /**
     * @return array<string>
     */
    function getAddressArray(): array;
}