<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor; 

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $actor = new Actor();
        $actor->setName('Imię1');
        $manager->persist($actor);

        $actor2 = new Actor();
        $actor2->setName('Imię2');
        $manager->persist($actor2);

        $actor3 = new Actor();
        $actor3->setName('Imię3');
        $manager->persist($actor3);
    
        $manager->flush();

        //Dodanie odniesienia do tabeli Movies
        $this->addReference('actor_1', $actor);
        $this->addReference('actor_2', $actor2);
        $this->addReference('actor_3', $actor3);

    }
}
