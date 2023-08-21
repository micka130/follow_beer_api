<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Repository\BeerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeerRepository::class)]
class Beer
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $abv = null;

    #[ORM\Column]
    private ?int $ibu = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'beers')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Brewery $brewery = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAbv(): ?float
    {
        return $this->abv;
    }

    public function setAbv(float $abv): static
    {
        $this->abv = $abv;

        return $this;
    }

    public function getIbu(): ?int
    {
        return $this->ibu;
    }

    public function setIbu(int $ibu): static
    {
        $this->ibu = $ibu;

        return $this;
    }

    public function getBrewery(): ?Brewery
    {
        return $this->brewery;
    }

    public function setBrewery(?Brewery $brewery): static
    {
        $this->brewery = $brewery;

        return $this;
    }
}
