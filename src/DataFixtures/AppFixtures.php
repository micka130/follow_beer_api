<?php

namespace App\DataFixtures;

use App\Factory\BeerFactory;
use App\Factory\BreweryFactory;
use App\Factory\CheckinFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private BreweryFactory $breweryFactory;
    private BeerFactory $beerFactory;
    private CheckinFactory $checkinFactory;
    private UserFactory $userFactory;

    public function __construct(
        BreweryFactory $breweryFactory,
        BeerFactory $beerFactory,
        CheckinFactory $checkinFactory,
        UserFactory $userFactory
    ) {
        $this->breweryFactory = $breweryFactory;
        $this->beerFactory = $beerFactory;
        $this->checkinFactory = $checkinFactory;
        $this->userFactory = $userFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $this->provideApiFixtures($manager);


        $manager->flush();
    }

    private function provideApiFixtures(ObjectManager $manager): void
    {
        $breweries = [
            $this->breweryFactory->createBrewery('Brewery 1'),
            $this->breweryFactory->createBrewery('Brewery 2'),
            $this->breweryFactory->createBrewery('Brewery 3'),
        ];
        $this->bulkInsert($manager, $breweries);

        $beers = [
            $this->beerFactory->createBeer('Beer 1', 5.5, 60, $breweries[0]),
            $this->beerFactory->createBeer('Beer 2', 4.0, 84, $breweries[0]),
            $this->beerFactory->createBeer('Beer 3', 3.5, 49, $breweries[1]),
            $this->beerFactory->createBeer('Beer 4', 6.7, 55, $breweries[2]),
        ];
        $this->bulkInsert($manager, $beers);

        $users = [
            $this->userFactory->createUser('user1@acme.fr', 'user1', 'afo86jbdxdh5gp5j'),
            $this->userFactory->createUser('user2@acme.fr', 'user2', '96exrjkjde3shy7b'),
            $this->userFactory->createUser('user3@acme.fr', 'user3', 'r3cdbbcfdoeojkyo'),
        ];
        $this->bulkInsert($manager, $users);

        $checkins = [
            $this->checkinFactory->createCheckin(5.8, $beers[0], $users[0]),
            $this->checkinFactory->createCheckin(6.5, $beers[0], $users[1]),
            $this->checkinFactory->createCheckin(10, $beers[1], $users[2]),
            $this->checkinFactory->createCheckin(8.3, $beers[2], $users[2]),
        ];
        $this->bulkInsert($manager, $checkins);
    }

    private function bulkInsert(ObjectManager $manager, array $entities)
    {
        foreach ($entities as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
