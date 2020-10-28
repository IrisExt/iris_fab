<?php

namespace App\Tests\Manager;

use App\Entity\TgAffectation;
use App\Entity\TgProjet;
use App\Entity\FtCommandeApp;
use App\Manager\AffectationManager;
use App\Repository\TgAffectationRepository;
use App\Repository\TgProjetRepository;
use App\Repository\FtCommandeAppRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AffectationManagerTest extends TestCase
{
    protected $tgAffectationRepository;
    protected $tgProjetRepository;
    protected $ftCommandeAppRepository;
    protected $em;
    protected $affectationManager;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->tgAffectationRepository = $this->createMock(TgAffectationRepository::class);
        $this->tgProjetRepository = $this->createMock(TgProjetRepository::class);
        $this->ftCommandeAppRepository = $this->createMock(FtCommandeAppRepository::class);
        //$this->affectationManager = $this->createMock(AffectationManager::class, ['setDateRendu'], [$this->em, $this->tgAffectationRepository, $this->tgProjetRepository, $this->ftCommandeAppRepository]);
        $this->affectationManager = new AffectationManager($this->em, $this->tgAffectationRepository, $this->tgProjetRepository, $this->ftCommandeAppRepository);
    }

    /** @test */
    public function set_date_rendu_evaluation_should_work()
    {
        $tgAffectation = $this->createMock(TgAffectation::class);
        $this->tgAffectationRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                  $this->anything(),
                  $this->anything(),
            ])
            ->willReturn($tgAffectation);

        $this->assertTrue($this->affectationManager->setDateRendu(536, 1235, '29/10/2020'));
    }
}