<?php

namespace App\Factory;

use App\Entity\Beer;
use App\Entity\Brewery;

class BeerFactory
{
    public function createBeer(
        string $name,
        float $abv,
        int $ibu,
        ?Brewery $brewery = null
    ): Beer
    {
        $beer = new Beer();
        $beer->setName($name);
        $beer->setAbv($abv);
        $beer->setIbu($ibu);
        $beer->setBrewery($brewery);

        return $beer;
    }
}
