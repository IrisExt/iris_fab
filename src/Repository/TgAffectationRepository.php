<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAffectation;
use App\Repository\TrCriticiteRepository;
use App\Repository\TlStsEvaluationRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class TgAffectationRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    private $trCriticiteRepository;
    private $tlStsEvaluationRepository;

    /**
     * TrTypeDocRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        TrCriticiteRepository $trCriticiteRepository,
        TlStsEvaluationRepository $tlStsEvaluationRepository
    ) {
        parent::__construct($registry, TgAffectation::class);
        $this->entityManager = $entityManager;
        $this->trCriticiteRepository = $trCriticiteRepository;
        $this->tlStsEvaluationRepository = $tlStsEvaluationRepository;
    }


    /**
     * @param $comite
     * @return mixed
     */
    public function affectationRL($comite)
    {

        $db = $this->createQueryBuilder('a')
            ->leftJoin('a.idProjet', 'p')
            ->where('p.idComite = :comite')
            ->setParameter('comite', $comite)
            ->getQuery();

        return $db->getResult();
    }

    public function affectationExpComite($proj, array $sollic)
    {

        $db = $this->createQueryBuilder('a')
            ->leftJoin('a.idProjet', 'p')
            ->where('p.idProjet = :proj')
            ->andWhere('a.cdSollicitation in (:sollic)')
            ->setParameter('proj', $proj)
            ->setParameter('sollic', $sollic)
            ->getQuery();
        return $db->getResult();
    }

    public function setCriticiteToProject($projet)
    {
        // Quotas
        $nbMinEvalAccept = 0;
        $nbMinEvalSoum = 0;

        // Evaluation
        $arrayEvaluationSoumise = [];
        $arrayEvaluationEnCours = [];
        $nbEvaluationSoumise = 0;
        $nbEvaluationEnCours = 0;

        // Evaluateur
        $arrayEvaluateurAccepte = [];
        $arrayEvaluateurSollicite = [];
        $arrayEvaluateurSansReponse = [];
        $nbEvaluateurAccepte = 0;
        $nbEvaluateurSollicite = 0;
        $nbEvaluateurSansReponse = 0;

        // Criticité
        $codeCriticite = 0;

        // quotas evaluation pour chaque projet
        $comite = $projet->getIdComite();
        if (isset($comite)) {
            // quotas min evaluation accepte
            $nbMinEvalAccept = $comite->getNbMinEvalAccept();
            // quotas min evaluation soumise
            $nbMinEvalSoum = $comite->getNbMinEvalSoum();
        }

        // affectations pour le projet
        $listAffectation = $projet->getTgAffectations();
        foreach ($listAffectation as $affectation) {

            // statut évaluation
            $cdStsEvaluation = $affectation->getCdStsEvaluation();
            if (isset($cdStsEvaluation)) {
                // évaluations soumises pour chaque projet
                if ($cdStsEvaluation->getCdStsEvaluation() == 'SOM') {
                    array_push($arrayEvaluationSoumise, $projet->getIdProjet());
                }
                // évaluations en cours pour chaque projet
                if ($cdStsEvaluation->getCdStsEvaluation() == 'ENC') {
                    array_push($arrayEvaluationEnCours, $projet->getIdProjet());
                }
            }

            // statut évaluateur
            $cdSollicitation = $affectation->getCdSollicitation();
            if (isset($cdSollicitation)) {
                // évaluateurs acceptés par projet
                if ($cdSollicitation->getCdSollicitation() == 'ACC') {
                    array_push($arrayEvaluateurAccepte, $projet->getIdProjet());
                }
                // évaluateurs sollicités par projet
                if ($cdSollicitation->getCdSollicitation() == 'SOL') {
                    array_push($arrayEvaluateurSollicite, $projet->getIdProjet());
                }
                // évaluateurs sollicités par projet
                if ($cdSollicitation->getCdSollicitation() == 'SSR') {
                    array_push($arrayEvaluateurSansReponse, $projet->getIdProjet());
                }
            }
        }

        // Evaluation
        // nb d'évaluation soumise
        if (!empty($arrayEvaluationSoumise)) {
            $nbEvaluationSoumise = array_values(array_count_values($arrayEvaluationSoumise))[0];
        }
        // nb d'évaluation en cours
        if (!empty($arrayEvaluationEnCours)) {
            $nbEvaluationEnCours = array_values(array_count_values($arrayEvaluationEnCours))[0];
        }

        // Evaluateur
        // nb d'évaluateur accepté
        if (!empty($arrayEvaluateurAccepte)) {
            $nbEvaluateurAccepte = array_values(array_count_values($arrayEvaluateurAccepte))[0];
        }
        // nb d'évaluateur sollicité
        if (!empty($arrayEvaluateurSollicite)) {
            $nbEvaluateurSollicite = array_values(array_count_values($arrayEvaluateurSollicite))[0];
        }
        // nb d'évaluateur sans réponse
        if (!empty($arrayEvaluateurSansReponse)) {
            $nbEvaluateurSansReponse = array_values(array_count_values($arrayEvaluateurSansReponse))[0];
        }

        // code de criticité
        if ($nbEvaluationSoumise >= $nbMinEvalSoum) {
            $codeCriticite = 7;
        } else if (
            $nbEvaluationSoumise +
            $nbEvaluationEnCours >= $nbMinEvalSoum
        ) {
            $codeCriticite = 6;
        } else if ($nbEvaluateurAccepte >= $nbMinEvalAccept) {
            $codeCriticite = 5;
        } else if (
            $nbEvaluateurAccepte +
            $nbEvaluateurSollicite >= $nbMinEvalAccept
        ) {
            $codeCriticite = 4;
        } else if (
            $nbEvaluationSoumise +
            $nbEvaluateurSollicite +
            $nbEvaluateurAccepte >= $nbMinEvalAccept
        ) {
            $codeCriticite = 3;
        } else if (
            ($nbEvaluationSoumise +
                $nbEvaluationEnCours +
                $nbEvaluateurAccepte +
                $nbEvaluateurSollicite +
                $nbEvaluateurSansReponse) == $nbMinEvalAccept
        ) {
            $codeCriticite = 2;
        } else if (
            ($nbEvaluationSoumise +
                $nbEvaluationEnCours +
                $nbEvaluateurAccepte +
                $nbEvaluateurSollicite +
                $nbEvaluateurSansReponse) < $nbMinEvalAccept &&
            ($nbEvaluationSoumise +
                $nbEvaluationEnCours +
                $nbEvaluateurAccepte +
                $nbEvaluateurSollicite +
                $nbEvaluateurSansReponse) > 0
        ) {
            $codeCriticite = 1;
        } else if (
            $nbEvaluateurSollicite == 0 ||
            $nbEvaluateurAccepte == 0 ||
            $nbEvaluationSoumise == 0
        ) {
            $codeCriticite = 0;
        }

        // rajout de la criticite au projet
        $projet->criticite = $this->trCriticiteRepository->findBy(['codeCriticite' => $codeCriticite]);

        // retour du projet avec la criticite
        return $projet;
    }
}
