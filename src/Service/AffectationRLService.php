<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\TgComite;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TlAvisProjet;
use App\Entity\TrAvisProjet;
use App\Repository\TgProjetRepository;
use App\Repository\TrAvisProjetRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Composer\DependencyResolver\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\TlAvisProjetRepository;

/**
 * Class AffectationRLService
 *
 * @package App\Service
 */
class AffectationRLService
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var TrAvisProjet
     */
    private $trAvisProjetRepository;

    /**
     * @var TgProjetRepository
     */
    private $tgProjetRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TlAvisProjetRepository
     */
    private $avisProjetRepository;

    /**
     * AffectationRLService constructor.
     *
     * @param ManagerRegistry $registry
     * @param TrAvisProjetRepository $trAvisProjetRepository
     * @param TgProjetRepository $tgProjetRepository
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ManagerRegistry $registry,
        TrAvisProjetRepository $trAvisProjetRepository,
        TgProjetRepository $tgProjetRepository,
        SessionInterface $session,
        TranslatorInterface $translator,
        TlAvisProjetRepository $avisProjetRepository
    ) {
        $this->registry = $registry;
        $this->trAvisProjetRepository = $trAvisProjetRepository;
        $this->tgProjetRepository = $tgProjetRepository;
        $this->session = $session;
        $this->translator = $translator;
        $this->avisProjetRepository = $avisProjetRepository;

    }

    /**
     * @param $object
     */
    public function save($object): void
    {
        $em = $this->registry->getManager();
        $em->persist($object);
        $em->flush();
    }

    public function avisMmbreAdd(TgPersonne $tgPersonne, $avisProjets, TgParticipation $tgParticipant, TgComite $tgComite, $soumettre)
    {

        try {
            foreach ($avisProjets as $avisProjet) {

                $tgProjet = $this->tgProjetRepository->find($avisProjet['projet']);
                $trAvisProjet = $this->trAvisProjetRepository->find($avisProjet['avis']);

                $tlAvisProjet = $this->avisProjetRepository->findOneBy(['idPersonne' => $tgPersonne->getIdPersonne(), 'idProjet' => $tgProjet->getIdProjet()])?: new TlAvisProjet();


                if($avisProjet['avis'] == 0) {
                    $this->registry->getManager()->remove($tlAvisProjet);
                    $this->registry->getManager()->flush();
                }else{
                    $tlAvisProjet
                        ->setIdProjet($tgProjet)
                        ->setIdPersonne($tgPersonne)
                        ->setCdAvis($trAvisProjet);
                    $this->save($tlAvisProjet);
                }
            }

            if (1 == $soumettre) {
                $tgParticipant->setQuestSoum(true);
                $this->save($tgParticipant);

                $nbQuestSoum = $tgComite->getNbQuestSoum() + 1;
                $tgComite->setNbQuestSoum($nbQuestSoum);
                $this->save($tgComite);
            }

//            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.avisProjet'));

        } catch (\Exception $e) {
            dd($e);
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.avisProjet'));
        }

    }


}