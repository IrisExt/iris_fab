<?php


namespace App\Controller;

use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgMotCleCps;
use App\Entity\TgParticipation;
use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use App\Entity\TgPhase;
use App\Entity\TrEtatSol;
use App\Entity\TrProfil;
use App\Entity\TrRole;
use App\Form\PersCpsType;
use App\Form\SollicitationType;
use App\Manager\ConstitutionCesManager;
use App\Manager\HabilitationManager;
use App\Manager\PersonneManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use http\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;


/**
 * Class ConstitutionCesController
 * @package App\Controller
 * @Route("/constces")

 */
class ConstitutionCesController extends BaseController
{

    private $em;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var PersonneManager
     */
    private $personneManager;
    /**
     * @var HabilitationManager
     */
    private $habilitationManager;
    /**
     * @var ConstitutionCesManager
     */
    private $cesManager;


    /**
     * ConstitutionCesController constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param PersonneManager $personneManager
     * @param HabilitationManager $habilitationManager
     * @param ConstitutionCesManager $cesManager
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session, PersonneManager $personneManager, HabilitationManager $habilitationManager, ConstitutionCesManager $cesManager)
    {
        $this->em = $em;
        $this->session = $session;
        $this->personneManager = $personneManager;
        $this->habilitationManager = $habilitationManager;
        $this->cesManager = $cesManager;
    }

    /**
     * @param $appel
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/selcomite", name="select_comite")
     * @IsGranted({"ROLE_DOS_EM", "ROLE_PILOTE", "ROLE_PRES", "ROLE_VISE_PRES","ROLE_RE_SCI","ROLE_GE_COSSE","ROLE_RE_DEP","ROLE_CPS"})
     */
    public function selmembre(Request $request, PaginatorInterface $paginator): Response
    {
        $comite = $this->getEmComite()->findLstcomite( $this->session->get('appel'));

        $comitePart = $this->getEmHabil()->userParticipComite($this->getUserConnect());


        $pilote = $this->getEmHabil()->piloteAppelAcces( $this->session->get('appel'), $this->getUserConnect()); //droit s'il est pilote de l'appel

        $comiteSelec = $request->get('selectComite');

        if ($comiteSelec) {
            $idcomite = $request->get('selectComite');

            return $this->redirect($this->generateUrl('lst_membre', ['idComite' => $idcomite]));
        }
        return $this->render('constitutionces/selecmembre.html.twig',
            [
                'comites' => $comite,
                'comitePart' => $comitePart,
                'pilote' => $pilote
            ]);
    }

    /**
     * Gestion de la liste des membres d’un comité
     * @Route("/gstMembre/{idComite}", name="lst_membre")
     */
    public function lstmembre(Request $request, PaginatorInterface $paginator, TgComite $idComite)
    {

        $emParti = $this->getEmPartic();
        //return les habils,comite,appel
        $habByCmteAppels = $this->getEmHabil()
            ->habilByComiteAndAppelAndPers($idComite->getIdComite(),  $this->session->get('appel')->getIdAppel(), $this->getUserConnect()->getIdPersonne());

        if (empty($habByCmteAppels) or $this->isGranted('ROLE_MBRE') == true) { // refus s'il n'est pas DOS_EM ou Pilote de l'appel ou CPS p,s Du comité
            throw new AccessDeniedException();
        }
        // prio groupe
        if ($request->query->get('participant')) {
            foreach ($request->query->get('participant') as $prio_now => $id) {
                $emParti->updatePrioGroupe($id, $prio_now);
            }
            $this->addFlash('success', 'Les priorités groupe ont été mises à jour ');
            return $this->redirect($request->headers->get('referer'));
        }

        $phaseRef = $this->getEmPhase()->findOneBy(['idPhase' =>  $this->session->get('phase')]);
        $tt = $emParti->findBy(['idComite' => $idComite, 'idPhaseRef' => $phaseRef->getIdPhaseRef()],['prioGrp'=>'ASC']);


        $mmbreComite = $paginator->paginate(
//            $emParti->findPartParComiteCes($comite,  $this->session->get('phase'),  $this->session->get('appel')),
            $emParti->findBy(['idComite' => $idComite, 'idPhaseRef' => $phaseRef->getIdPhaseRef()],['prioGrp'=>'ASC']),
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );


        $statContacte = count($emParti->findBy(['idComite' => $idComite, 'cdEtatSollicitation' => 5]));
        $statAccepte = count($emParti->findBy(['idComite' => $idComite, 'cdEtatSollicitation' => 6]));

        $participantComite = $emParti->findBy(['idComite' => $idComite]);
        return $this->render("constitutionces/lstmembre.html.twig",
            [
                'participComite' => $participantComite,
                'comite' => $idComite,
                'mmbreComites' => $mmbreComite,
                'statContacte' => $statContacte,
                'statAccepte' => $statAccepte,
                'apstatut' => $this->APstatut( $this->session->get('appel')),
            ]);
    }

    /**
     * modal ajouter evaluateur
     * Enregistrement dans personne, cpspers, participation
     * @Route("/enregisEvaluateur/{idComite}", name="enregistrer_evalua_comite")
     * @isGranted({"ROLE_CPS", "ROLE_PRES", "ROLE_VISE_PRES"})
     */
    public function ajouterevaluateurComite(Request $request, PaginatorInterface $paginator, TgComite $idComite)
    {
        $this->appelClos( $this->session->get('appel')); // retour 403 / Accès refusé si appel est clos
        $PersCps = new TgPersCps();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PersCpsType::class, $PersCps);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $appel_object = $this->getEmAppPro()->find($this->session->get('appel'));
            $phase_object = $appel_object->getNiveauEnCours()->getIdPhase();
            $sollicitation = $this->getDoctrine()->getRepository(TrEtatSol::class)->find(3);
            $genre = $form->get('idGenre')->getData();
            $profil = $form->get('profil')->getData();

            try {
                $em->persist($PersCps);
                // insert to tgpersonne and return $tgPersonne
               $personne = $this->personneManager->setTgPersonne($request->get('pers_cps')['lbNomFr'],$request->get('pers_cps')['lbPrenom'],$genre,$PersCps);

                $motCles = explode(',', $request->get('pers_cps')['motcle']);
                foreach ($motCles as $motCle) {
                    $motcle = new TgMotCleCps();
                    $motcle
                        ->setIdPersonne($personne)
                        ->setLbMcCpsFr($motCle);
                    $em->persist($motcle);
                }
                $this->habilitationManager->setHabilitation($personne,$profil,$idComite,null,$phase_object,null);
                $this->cesManager->setNewParticipComite($personne,$profil,$idComite,$phase_object->getIdPhaseRef(),$sollicitation);

                $metadata = $em->getClassMetaData(get_class($personne));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new AssignedGenerator());

                $em->flush();

            } catch (\Exception $e) {
                $this->addFlash('error', "L'évaluateur n'a pas été créé");
            }

            $this->addFlash('success', 'L\'évaluateur ' . $form->get('lbNomFr')->getData() . ' ' . $form->get('lbPrenom')->getData() . ' a bien été créé'); // un message flash pour confirmer la création du membre
            return $this->redirectToRoute('lst_membre', ['idComite' => $idComite->getIdComite()]); // redirection vers liste des comites
        }
        return $this->render('constitutionces/modal/ajouterevaluateur.htm.twig',
            [
                'comite' => $idComite,
                'form' => $form->createView(),

            ]);
    }

    /**
     * @param Request $request
     * @param TgPersonne $personne
     * @param TgComite $idcomite
     * @return Response
     * @throws \Exception
     * ajouter personne deja existante
     * @Route("/enregisEvalExis/{personne}/{idComite}", name="enregistrer_evalua_existant_comite")
     */
    public function ajouterMembreExistant(Request $request, TgPersonne $personne, TgComite $idComite): Response
    {
        $this->appelClos( $this->session->get('appel')); // retour 403 / Accès refusé si appel est clos
        if ($this->isCsrfTokenValid('post' . $personne->getIdPersonne(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $appel_object = $this->getEmAppPro()->find( $this->session->get('appel'));
            $phase_object = $appel_object->getNiveauEnCours()->getIdPhase();
            $profil = $this->getEmProfil($request->get('profil'));
            $sollicitation = $this->getDoctrine()->getRepository(TrEtatSol::class)->find(3);
            $persParti = $this->getEmPartic()->findParticipByAppel($appel_object, $personne); // membre qui participe dans un comite  d'un appel

            if (!empty($persParti)) { // personne participe dans un comité
                foreach ($persParti as $key => $cmte) {
                    $comites[] = $idComite->getLbAcr();
                };
                $this->addFlash('error', 'Impossible d\'ajouter ce membre,' . $personne . ' est dans le Comite : ' . implode(",", $comites));
                return $this->redirect($request->headers->get('referer'));
            };

            $habilExiste = $this->getEmHabil()->findOneBy(['idPersonne' => $personne, 'idProfil' => $profil]);
            $phase = $this->getEmPhase()->find( $this->session->get('phase'));
            if (empty($habilExiste)) {
                $this->habilitationManager->setHabilitation($personne,$profil,$idComite,null,$phase_object,null);
            } else {
                $habilExiste->AddIdPhase($phase)
                            ->AddIdComite($idComite);
                $em->persist($habilExiste);
            }
            $this->cesManager->setNewParticipComite($personne,$profil,$idComite,$phase_object->getIdPhaseRef(),$sollicitation);
            $this->em->flush();

            $this->addFlash('success', ' Un membre ' . $personne->getLbNomUsage() . ' ' . $personne->getLbPrenom() . ' est ajouté ');
            return $this->redirectToRoute('lst_membre', ['idComite' => $idComite->getIdComite()]);
        }
        $this->addFlash('error', 'Le membre ' . $personne->getLbNomUsage() . ' ' . $personne->getLbPrenom() . ' n\'est ajouté ');
        return $this->redirectToRoute('lst_membre', ['idComite' => $idComite->getIdComite()]);


    }

    /**
     * @param Request $request
     * @param TgPersonne $personne
     * @param TrProfil $profil
     * @param TgParticipation $participation
     * @param TgComite $comite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("modif/evalua/{idPersonne}/{idProfil}/{idParticipation}/{idComite}", name="modif_evalua")
     */
    public function modifierEvaluateur(Request $request, TgPersonne $personne, TrProfil $profil, TgParticipation $participation, TgComite $comite)
    {
        $this->appelClos( $this->session->get('appel')); // retour 403 / Accès refusé si appel est clos
        $motcle = $this->getDoctrine()->getRepository(TgMotCleCps::class)->findBy(['idPersonne' => $personne]);
//        $participGroupe = $this->getEmPartic()->findOneBy([''])

        $form = $this->createForm(PersCpsType::class, $personne->getIdPersCps(),
            [
                'modif' => true,
                'participGroupe' => $participation,
                'motcle' => $motcle,
                'profil' => $profil,
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $perscps = $personne->getIdPersCps();

            $profilAnci = $participation->getIdProfil();

            if ($profilAnci != $form->get('profil')->getData()) {
                $habilExiste = $this->getEmHabil()->findOneBy(['idPersonne' => $personne, 'idProfil' => $form->get('profil')->getData()]);
                $phase = $em->getRepository(TgPhase::class)->find( $this->session->get('phase'));
                if (empty($habilExiste)) {
                    $this->habilitationManager->setHabilitation($personne,$form->get('profil')->getData(),$comite,null,$phase,null);
                } else {
                    $habilExiste->AddIdPhase($phase)
                        ->AddIdComite($comite);
                    $em->persist($habilExiste);
                }
            };
            $participation->setLbGroupe($form->get('groupe')->getData());
            $participation->setIdProfil($form->get('profil')->getData());
            $em->persist($participation);

            if (!$motcle) { // si mot cle n'existe pas creer un
                $motcle = new TgMotCleCps();
                $motcle->setIdPersonne($personne)
                    ->setLbMcCpsFr($request->get('pers_cps')['motcle']);
                $em->persist($motcle);
            } else {
                foreach ($motcle as $mtcleSupp) { // suppression des object
                    $em->remove($mtcleSupp);
                }
                $motCles = explode(',', $request->get('pers_cps')['motcle']);
                foreach ($motCles as $motCleCps) {
                    $motcle = new TgMotCleCps();
                    $motcle->setIdPersonne($personne)
                        ->setLbMcCpsFr($motCleCps);
                    $em->persist($motcle);
                }
            }
            if (false == $personne->getCvRenseigne()) { // si l'evaluateur n'a pas encore transmis son Cv on peut modifier la personne
                $genre = $form->get('idGenre')->getData();
                $personne->setIdGenre($genre)
                    ->setLbNomUsage($perscps->getLbNomFr())
                    ->setLbPrenom($perscps->getLbPrenom());
                $em->persist($personne);
                $em->persist($perscps);
            }

            $em->flush(); // flush sur cpsPers et personne
            return $this->redirect($request->headers->get('referer'));
        };
        return $this->render('constitutionces/modal/modifevaluateur.html.twig', [
            'personne' => $personne,
            'profil' => $profil,
            'participation' => $participation,
            'perscps' => $personne->getIdPersCps(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * supprimé un membre dans constitution CES
     * @Route("/delete/{idPersCps}/{idProfil}/{idParticipation}" , name="pers_cps_delete_pers", methods={"DELETE"})
     * @isGranted({"ROLE_CPS", "ROLE_PRES", "ROLE_VISE_PRES"})
     */
    public function deleteMembre(Request $request, TgPersCps $persCps, TrProfil $profil, TgParticipation $participation): Response
    {
        $this->appelClos( $this->session->get('appel')); // retour 403 / Accès refusé si appel est clos
        if ($this->isCsrfTokenValid('delete' . $persCps->getIdPersCps(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();

            $habilitation = $this->getEmHabil()->findOneBy(['idPersonne' => $persCps->getIdPersonne() , 'idProfil' => $profil]);
            $phase= $this->getEmPhase()->find( $this->session->get('phase')->getIdPhase());

            try {
                $habilitation->removeIdComite($participation->getIdComite())
                    ->removeIdPhase($phase);
                $em->persist($habilitation);

                $em->remove($participation);
            } catch (Exception $e) {
                $this->addFlash('error', "L'évaluateur n'a pas été supprimé");
            }
            $em->flush();
            $this->addFlash('success', 'Le membre à bien été supprimé');
            return $this->redirect($request->headers->get('referer')); // redirect
        }

        $this->addFlash('error', 'Le membre n\'à été supprimé');
        return $this->redirect($request->headers->get('referer')); // redirect
    }

    /**
     * @Route("/ordgrp/{idComite}" , name="ord_grp")
     * @isGranted({"ROLE_CPS", "ROLE_PRES", "ROLE_VISE_PRES"})
     */
    public function ordgroupe(Request $request, TgComite $comite): Response
    {
        $personne = new TgPersonne();
        $participation = new TgParticipation();
        $em = $this->getDoctrine()->getManager();
        $mmbreCmte = $this->getEmPartic()->requetOrdreParti($comite);

        return $this->render('constitutionces/modal/ordregrp.hml.twig',
            [
                'mmbreCmte' => $mmbreCmte
            ]);
    }

    /**
     * @Route("/changer/sollicit/{idParticipation}", name="changer_sollicit")
     */
    public function changerSollicitation(Request $request, TgParticipation $participation)
    {
        $this->appelClos( $this->session->get('appel')); // retour 403 / Accès refusé si appel est clos
        $form = $this->createForm(SollicitationType::class, $participation,
            [
                'sollicit' => $participation,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->flush();
            $this->addFlash('success', 'l\'etat de sollictation a été modifiée');
            return $this->redirect($request->headers->get('referer')); // redirect
        }

        return $this->render('constitutionces/modal/modifsollicitation.html.twig',
            [
                'participation' => $participation,
                'form' => $form->createView()
            ]);

    }

    public function memebreDansCes($personne, $comite)
    {
        $habilitation = $this->getEmHabil()->findBy(['idPersonne' => $personne, 'idPhase' =>  $this->session->get('phase'), 'idComite' => $comite]);

        if (!empty($habilitation)) {
            return true;
        } else
            return false;
    }

    public function mbreDansCesCpsPre($personne, $comite)
    {
        $habilitation = $this->getEmHabil()->findBy([
            'idPersonne' => $personne,
            'idProfil' => [6, 7],
            'idPhase' =>  $this->session->get('phase'),
            'idComite' => $comite
        ]);
        $resultat = empty($habilitation) ? false : true;

        return $resultat;
    }
}