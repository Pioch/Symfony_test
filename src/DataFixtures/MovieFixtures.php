<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie; //dodanie klasy z Entity

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('Tytuł 1');
        $movie->setReleaseYear(2008);
        $movie->setDescription('Opis');
        $movie->setImagePath('pictures/obrazek_kot1');
        //dodanie danych to tabeli łączącej dwie
        $movie->addActor($this->getReference('actor_1')); //odniesienie do aktora
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie); //wykonanie zapytania

        $movie2 = new Movie();
        $movie2->setTitle('Tytuł 2');
        $movie2->setReleaseYear(2010);
        $movie2->setDescription('Opis2');
        $movie2->setImagePath('https://ecsmedia.pl/c/jak-bawic-sie-z-kotem-8-pomyslow-na-zabawe-artilce.horizontal.large-img54767446.jpg');
        $movie2->addActor($this->getReference('actor_3'));
        $manager->persist($movie2);

        $manager->flush(); //pozwala na wykonanie obydwóch zapytań jednocześnie
    }
}
