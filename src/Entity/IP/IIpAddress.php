<?php


namespace App\Classes\IP;


interface IIpAddress
{
    public function getBinary(): string;
    public function setAddressArray(string $address): void;
}