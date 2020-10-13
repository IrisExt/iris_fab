<?php
namespace App\DataFixtures;


use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use Doctrine\Persistence\ObjectManager;

class TgPersCpsFixtures extends BaseFixture{

    public function loadData(ObjectManager $manager)
    {

        $this->createMany(TgPersCps::class, 50, function(TgPersCps $persCps, $count) {
            $nom = $this->faker->firstName;
            $prenom = $this->faker->lastName;
            $email = $prenom.'.'.$nom.'@'.$this->faker->freeEmailDomain;
            $persCps
                ->setLbNomFr($nom)
                ->setLbPrenom($prenom)
                ->setLbAdrMail($email);

        });
        $manager->flush();
    }
}