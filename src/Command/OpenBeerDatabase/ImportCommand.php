<?php

namespace App\Command\OpenBeerDatabase;

use App\Factory\BeerFactory;
use App\Factory\BreweryFactory;
use App\Manager\BeerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(name: 'app:open-beer-database:import')]
class ImportCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private BeerFactory $beerFactory;
    private BreweryFactory $breweryFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        BeerFactory $beerFactory,
        BreweryFactory $breweryFactory
    )
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->beerFactory = $beerFactory;
        $this->breweryFactory = $breweryFactory;
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to import an open beer database CSV file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import open beer database CSV file');

        try {
            $this->import($io);
        }  catch (\Throwable $e) {
            $io->error(['Error', $e->getMessage()]);

            return Command::FAILURE;
        }

        $io->success('Process successed !');

        return Command::SUCCESS;
    }

    private function import(SymfonyStyle $io): void
    {
        $filename = 'var/import/open-beer-database.csv';
        $openBeerData = $this->serializer->decode(file_get_contents($filename), 'csv', ['csv_delimiter' => ';']);

        $io->progressStart(count($openBeerData));

        $breweries = [];

        $batchSize = 20;
        $i = 1;
        foreach ($openBeerData as $row) {
            $breweryId = (int) $row['brewery_id'];

            if (!isset($breweries[$breweryId])) {
                $breweries[$breweryId] = $this->breweryFactory->createBrewery(
                    $row['Brewer'],
                    $row['Address'] ?? null,
                    $row['City'] ?? null,
                    $row['Country'] ?? null
                );
            }

            $beer = $this->beerFactory->createBeer(
                $row['Name'],
                round((float) $row['Alcohol By Volume'], 1),
                (int) $row['International Bitterness Units'],
                $breweries[$breweryId]
            );

            $this->entityManager->persist($beer);
            if (($i % $batchSize) === 0) {
                $this->entityManager->flush();
            }

            $i++;
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->progressFinish();
    }
}
