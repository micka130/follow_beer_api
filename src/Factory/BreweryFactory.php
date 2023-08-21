<?php

namespace App\Factory;

use App\Entity\Brewery;

class BreweryFactory
{
    public function createBrewery(
        string $name,
        ?string $street = null,
        ?string $city = null,
        ?string $country = null
    ): Brewery
    {
        $brewery = new Brewery();
        $brewery->setName($name);
        $brewery->setStreet($street);
        $brewery->setCity($city);
        $brewery->setCountry($country);

        return $brewery;
    }
}
