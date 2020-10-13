<?php
namespace App\DataFixtures;


use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use Doctrine\Persistence\ObjectManager;

class TgPersonneFixtures extends BaseFixture{

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(TgPersonne::class, 5, function(TgPersonne $tgPersonne, $count) {
            $tgPersonne
                ->setLbPrenom($this->faker->lastName)
                ->setLbNomUsage($this->faker->lastName);
//                ->setIdPersCps($this->getRandomReference(TgPersCps::class));
        });
        $manager->flush();
    }
}