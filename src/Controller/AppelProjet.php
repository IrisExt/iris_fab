<?php

namespace App\Controller;

use App\Entity\TgAppelProj;
use App\Entity\TgHabilitation;
use App\Entity\TgNiveauPhase;
use App\Entity\TgPhase;
use App\Entity\TrNiveau;
use App\Entity\TrPhase;
use App\Entity\TrProfil;
use App\Form\AppelProjetType;
use App\Manager\HabilitationManager;
use App\Service\InsertDataProvisional;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appel")
 * @IsGranted("ROLE_DOS_EM")
 */
class AppelProjet extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var HabilitationManager
     */
    private $habilitationManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AppelProjet constructor.
     * @param SessionInterface $session
     * @param HabilitationManager $habilitationManager
     * @param EntityManagerInterface $em
     */
    public function __construct(SessionInterface $session, HabilitationManager $habilitationManager, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->habilitationManager = $habilitationManager;
        $this->em = $em;
    }

    /**
     * @Route("/", name="tg_appel_proj_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tgAppelProjs = $this->getDoctrine()
            ->getRepository(TgAppelProj::class)
            ->findAll();

        return $this->render('appelprojet/index.html.twig', [
            'tg_appel_projs' => $tgAppelProjs,
        ]);
    }

    /**
     * @Route("/new", name="tg_appel_proj_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tgAppelProj = new TgAppelProj();
        $appelNb = $this->getDoctrine()->getRepository(TgAppelProj::class)->findAll();
        $nbAppel = count($appelNb); // s'il n'existe aucun appel mettre les Session Appel, et Phase
        $form = $this->createForm(AppelProjetType::class, $tgAppelProj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profil = $this->em->getRepository(TrProfil::class)->find(1);

            if ($this->similaireAcronyme($tgAppelProj->getLbAcronyme(), $tgAppelProj->getDtMillesime()) > 0) {
                $this->addFlash('error', ' L\'édition ' . $tgAppelProj->getDtMillesime() . 'avec l\'acronyme ' . $tgAppelProj->getLbAcronyme() . ' existe déja, Veuillez réessayer SVP !');

                return $this->render('appelprojet/new.html.twig', [
                    'tg_appel_proj' => $tgAppelProj,
                    'form' => $form->createView(),
                ]);
            }

            $habi = $tgAppelProj->getPilote() !== null ? $this->habilitationManager->getHabProfilPersonne($tgAppelProj->getPilote(), $profil) : null;

            try {
                $nombrePhase = $tgAppelProj->getNbPhase();
                for ($nbPhase = 1; $nbPhase <= $nombrePhase; ++$nbPhase) {
                    $phase = new TgPhase();
                    $phase->setIdPhaseRef($this->em->getRepository(TrPhase::class)->find($nbPhase));
                    $this->em->persist($phase);

                    for ($nbNivPhase = 1; $nbNivPhase <= 3; ++$nbNivPhase) {  // 3 est le niveau de phase Soum, evalu , edit
                        $nivPhase = new TgNiveauPhase();
                        $nivPhase->setIdAppel($tgAppelProj)
                            ->setIdPhase($phase)
                            ->setIdTypeNiveu($this->em->getRepository(TrNiveau::class)->find($nbNivPhase))
                            ->setOrdPhase($nbNivPhase);
                        $this->em->persist($nivPhase);
                    }
                    if ($habi === null && $tgAppelProj->getPilote() !== null) {
                        $this->habilitationManager->setHabilitation($tgAppelProj->getPilote(), $profil, null, $tgAppelProj, $phase, null);
                    } elseif ($habi !== null) {
                        $habi->addIdPhase($phase)
                            ->addIdAppel($tgAppelProj);
                        $this->em->persist($habi);
                    }
                }

                ////////////////////////////////////// Remplir tg_parametre et mot cle erc / test , -- Provisoire -- //////////////////
                $insertData = new InsertDataProvisional($this->em);
                $insertData->DataAapg($tgAppelProj);
                ///////////////////////////////////// FIN  tg_parametre and motCleErc/////////////////////////////////////////////////
                $this->em->persist($tgAppelProj);
                $this->em->flush();
            } catch (\Exception $e) {
                dd($e);
                $this->addFlash('error', 'Impossible de d\ajouter un appel ! Veuillez contacter  l\'administrateur');

                return $this->redirectToRoute('tg_appel_proj_index');
            }

            // chercher la phase 1 et niveau 1
            $nivAppel = $this->em->getRepository(TgNiveauPhase::class)->findOneBy(['idAppel' => $tgAppelProj], ['idNiveauPhase' => 'ASC']);
            $tgAppelProj->setNiveauEnCours($nivAppel);
            $this->em->persist($tgAppelProj);
            $this->em->flush();
            if (0 != $nbAppel) {
                $this->session->set('appel', $tgAppelProj);
                $this->session->set('phase', $nivAppel->getIdPhase());
            }

            return $this->redirectToRoute('tg_appel_proj_index');
        }

        return $this->render('appelprojet/new.html.twig', [
            'tg_appel_proj' => $tgAppelProj,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idAppel}", name="tg_appel_proj_show", methods={"GET"})
     */
    public function show(TgAppelProj $tgAppelProj): Response
    {
        return $this->render('appelprojet/show.html.twig', [
            'tg_appel_proj' => $tgAppelProj,
        ]);
    }

    /**
     * @Route("/{idAppel}/edit", name="tg_appel_proj_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TgAppelProj $tgAppelProj): Response
    {

        $form = $this->createForm(AppelProjetType::class, $tgAppelProj);
        $form->handleRequest($request);
        $profilPilote = $this->getDoctrine()->getRepository(TrProfil::class)->find(1);

        $habiAnc = $this->getDoctrine()->getRepository(TgHabilitation::class)->pilotebyAppel($tgAppelProj, $profilPilote);
        $personneAncien = ($habiAnc) ? $habiAnc[0]->getIdPersonne() : null;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->similaireAcronyme($tgAppelProj->getLbAcronyme(), $tgAppelProj->getDtMillesime(), $tgAppelProj->getIdAppel()) > 0) {
                $this->addFlash('error', ' L\'édition '.$tgAppelProj->getDtMillesime().'avec l\'acronyme '.$tgAppelProj->getLbAcronyme().' existe déja, Veuillez réessayer SVP !');

                return $this->render('appelprojet/new.html.twig', [
                    'tg_appel_proj' => $tgAppelProj,
                    'form' => $form->createView(),
                ]);
            }
            //Pilote
            $habilPiloteByAppel = $this->getDoctrine()->getRepository(TgHabilitation::class)
                ->findOneBy(['idPersonne' => $tgAppelProj->getPilote(), 'idProfil' => $profilPilote]);

            $phases = $this->getDoctrine()->getRepository(TgNiveauPhase::class)->phaseByAppel($tgAppelProj);

            foreach ($phases as $phaseAppel) {
                $phase = $this->getDoctrine()->getRepository(TgPhase::class)->find($phaseAppel['idPhase']);
                if ((!empty($habiAnc) && $personneAncien != $form->all()['pilote']->getData()) || empty($habiAnc)) {
                    if (!isset($habilPiloteByAppel)) {
                        if (null !== $form->all()['pilote']->getData()) {
                            $this->habilitationManager->setHabilitation($tgAppelProj->getPilote(), $profilPilote, null, $tgAppelProj, null, null);
                        }
                    } else {
                        $habilPiloteByAppel->addIdAppel($tgAppelProj)
                            ->addIdPhase($phase);
                        $this->em->persist($habilPiloteByAppel);
                    }
                    if (!empty($habiAnc)) {
                        if ($personneAncien != $form->all()['pilote']->getData()) {
                            $habiAnc[0]->removeIdAppel($tgAppelProj)
                                ->removeIdPhase($phase);
                            $this->em->persist($habiAnc[0]);
                        }
                    }
                }
            }
            $this->em->persist($tgAppelProj);
            $this->em->flush();

            return $this->redirectToRoute('tg_appel_proj_index', [
                'idAppel' => $tgAppelProj->getIdAppel(),
            ]);
        }

        return $this->render('appelprojet/edit.html.twig', [
            'tg_appel_proj' => $tgAppelProj,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idAppel}", name="tg_appel_proj_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TgAppelProj $tgAppelProj): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tgAppelProj->getIdAppel(), $request->request->get('_token'))) {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $tgNivPhases = $this->em->getRepository(TgNiveauPhase::class)->findBy(['idAppel' => $tgAppelProj]);
                $tgPhaseAppel = $this->em->getRepository(TgNiveauPhase::class)->phaseByAppel($tgAppelProj); // retourne les phases de l'appel
                $tghabs = $this->em->getRepository(TgHabilitation::class)->pilotebyAppel($tgAppelProj, 1); // hab profil pilote
                $tghabs[0]->removeIdAppel($tgAppelProj);
                $tgAppelProj->setNiveauEnCours(null); // set null for TgAppel
                $this->em->persist($tgAppelProj);
                $this->em->flush();

                foreach ($tgPhaseAppel as $tgPhase) {
                    $phase = $this->getDoctrine()->getRepository(TgPhase::class)->find($tgPhase['idPhase']);
                    foreach ($tghabs[0]->getIdPhase() as $phase_) {
                        if ($phase_ == $phase) {
                            $tghabs[0]->removeIdPhase($phase);
                        }
                    }
                }
                foreach ($tgNivPhases as $tgNivPhase) {
                    $this->em->remove($tgNivPhase);
                    $tgphase = $tgNivPhase->getIdPhase();
                    if ($tgphase) {
                        $this->em->remove($tgphase);
                    }
                }
                $this->em->remove($tgAppelProj);
                $this->em->flush();
            } catch (\Exception  $e) {
                $this->addFlash('error', 'Impossible de supprimer, d\'autres données sont liées à cette entité');

                return $this->redirectToRoute('tg_appel_proj_index');
            }
            if (0 == count($this->getDoctrine()->getRepository(TgAppelProj::class)->findAll())) {
                $this->session->set('appel', null);
                $this->session->set('phase', null);
            }
        }

        return $this->redirectToRoute('tg_appel_proj_index');
    }

    /**
     * Changement de l'aapg depuis le menu.
     *
     * @param $idAppel
     * @Route("/choixAAPG/{idAppel}", name="choix_aapg")
     */
    public function ChoixDeAapg(Request $request, $idAppel): Response
    {
        $this->session->set('appel', $idAppel);

        $aapgActuel = $this->getDoctrine()->getRepository(TgAppelProj::class)->find($idAppel);
        $this->addFlash('success', "Vous êtes actuellement  sur l '" . $aapgActuel);

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @param $acronyme
     *
     * @return bool|RedirectResponse
     *                               vérifier l'unicité de l'acronyme Pour l'appel
     */
    public function similaireAcronyme($acronyme, $millesime, $idAppel = null)
    {
        $appels = $this->getDoctrine()->getRepository(TgAppelProj::class)->findAll();
        if ($idAppel) {
            $appels = $this->getDoctrine()->getRepository(TgAppelProj::class)->findnotby($idAppel);
        }
        $compte = 0;
        foreach ($appels as $appel) {
            similar_text($appel->getLbAcronyme(), $acronyme, $percent);
            if (100.0 == $percent && $appel->getDtMillesime() == $millesime) {
                ++$compte;
            }
        }

        return $compte;
    }
}
