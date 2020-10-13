<?php

namespace App\Controller;

use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgParticipation;
use App\Entity\TrDepartement;
use App\Entity\TrEtatSol;
use App\Form\ComiteType;
use App\Form\ParticipationType;
use App\Service\InsertDataProvisional;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ComiteController.
 *
 * @Route("comite")
 */
class ComiteController extends BaseController
{
    /**
     * @var mixed
     */
    private $appel;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * ComiteController constructor.
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->appel = $session->get('appel');

        $this->session = $session;
    }

    /**
     * @Route("/list", name="list_comite")
     */
    public function lstcomite(Request $request, PaginatorInterface $paginator): Response
    {
        if (null === $this->appel) {
            return $this->redirectToRoute('accueil');
        }
        $tgComite = $this->getEmComite(); //em comite

        $dep = $request->query->get('dep'); //  recherche par département
        $cmte = $request->query->get('cmte'); // recherche par comité

        $comites = $tgComite->findBy(['idAppel' => $this->appel, 'blActif' => 1]);

        if ($dep) {
            $result = $tgComite->findLstcomiteDep($this->appel, $dep);
        } else {
            $result = ($cmte ? $tgComite
                ->findby(['idComite' => $cmte, 'idAppel' => $this->appel, 'blActif' => 1])
                : $comites);
        }

        $comite = $paginator->paginate($result, $request->query->getInt('page', 1), 50);

        // retour  :les départements
        $dep = $this->em->getRepository(TrDepartement::class)->depComiteExiste($this->appel);

        // les accès  pour les participants
        $comitePart = $this->getEmHabil()->userParticipComite($this->getUserConnect());

        return $this->render('comite/listcomite.html.twig', [
            'appel' => $this->appel->getLbAcronyme(),
            'cmte' => $comites,
            'comites' => $comite, // après pagination
            'deps' => $dep,
            'comitePart' => $comitePart,
            'apstatut' => $this->APstatut($this->appel),
        ]);
    }

    /**
     * @Route("/ajout/ajouterComite", name="ajouter_comite", methods={"GET", "POST"})
     * @IsGranted("ROLE_PILOTE")
     * modal comite
     */
    public function creercomite(Request $request): Response
    {
        $comite = new TgComite();
        $form = $this->createForm(ComiteType::class, $comite,
            [
                'appel' => $this->session->get('appel'),
            ]);

        $form->handleRequest($request);

        return $this->render('comite/creercomitemodal.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @throws Exception
     * @Route("/delete/{idComite}", name="annuler_comite", methods={"POST"}))
     * @IsGranted("ROLE_PILOTE")
     */
    public function annulerComite(Request $request, TgComite $comite): RedirectResponse
    {
        $this->appelClos($this->appel); // retour 403 / Accès refusé si appel est clos

        if ($this->isCsrfTokenValid('annuler'.$comite->getIdComite(), $request->request->get('_token'))) {
            $habilitation = $this->getEmHabil()->PersonnehabiliteComite($comite);
            foreach ($habilitation as $profilmmbre) {
                if (in_array($profilmmbre->getIdProfil()->getIdProfil(), [4, 8, 9], true)) {
                    $this->addFlash('error', 'Au moins un membre ou un président  est rattaché à ce comité, 
                    Impossible de supprimer');

                    return $this->redirectToRoute('list_comite');
                }
            }
            foreach ($habilitation as $perhabi) {
                $perhabi->removeIdComite($comite);
                $this->getEm()->persist($perhabi);
            }
            $comite->setBlActif(0);
            $this->getEm()->persist($comite);

            $this->getEm()->flush();
            // un message flash pour confirmer la création du comité
            $this->addFlash('success', 'Le comité '.$comite->getLbAcr().' a bien été supprimé');
        }

        return $this->redirectToRoute('list_comite');
    }

    /**
     * @Route("/comites", name="comite_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $partici = $this->getEmPartic()->findAllPartComite(); // trouver les occurences du comité concerné

        $comites = $paginator->paginate(
            $this->getEmComite()->findLstcomite($this->appel),
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('comite/gestioncomite/liste.html.twig', [
            'comites' => $comites,
            'particips' => $partici,
        ]);
    }

    /**
     * @Route("/nouveau", name="comite_nv", methods={"GET","POST"})
     * @IsGranted("ROLE_PILOTE")
     */
    public function new(Request $request): Response
    {
        $Comite = new TgComite();

        $form = $this->createForm(ComiteType::class, $Comite, [
            'appel' => $this->session->get('appel'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->similaireAcronComite($Comite->getLbAcr()) > 0) {
                $this->addFlash('error', 'l\'acronyme '.$Comite->getLbAcr().' existe déja pour cet appel');

                return $this->render('comite/gestioncomite/new.html.twig', [
                    'tg_comite' => $Comite,
                    'form' => $form->createView(),
                ]);
            }

            try {
                $sollicitation = $this->em->getRepository(TrEtatSol::class)->find(6);
                // trouvé l'entity correspondante de chaque role, et phase
                $profil_pres = $this->getEmProfil(4); // id 4 président
                $profil_cpsP = $this->getEmProfil(6); // id 6 correspond à cps Prin
                $profil_cpsSe = $this->getEmProfil(7); // id 7 cps Sec
//                $appel = $this->getEmAppPro()->find($this->session->get('appel'));
                $phase = $this->appel->getNiveauEnCours()->getIdPhase();
                // création du comité actif

                $Comite->setBlActif(1);
                $this->getEm()->persist($Comite);

                $president = $form->all()['president']->getData(); // l'objet personne (président) du form
                if (null !== $president) {
                    $this->inserToParticipation($president, $Comite, $phase->getIdPhaseRef(), $sollicitation, $profil_pres);
                    $habiExiste = $this->getEmHabil()->findOneBy(['idProfil' => $profil_pres, 'idPersonne' => $president]);
                    if (!empty($habiExiste)) { // $abilitaion existe
                        $habiExiste->AddIdComite($Comite);
                        $this->em->persist($habiExiste);
                    } else {
                        $this->inserToHabilitation($president, $profil_pres, $Comite);
                    }
                }

                // boucle pour les personne cps Prin. et persist
                foreach ($form->all()['cpsprincipal']->getData() as $cpsPrin) {
                    $habiExiste = $this->getEmHabil()->findOneBy(['idProfil' => $profil_cpsP, 'idPersonne' => $cpsPrin]);

                    if (!empty($habiExiste)) { // $abilitaion appel existe pas
                        $habiExiste->AddIdComite($Comite);
                        $this->em->persist($habiExiste);
                    } else {
                        $this->inserToHabilitation($cpsPrin, $profil_cpsP, $Comite);
                    }
                }

                // boucle pour les personne cps Sec
                $cpsSecondaire = $form->all()['cpsSecondaire']->getData();
                if (null !== $cpsSecondaire) {
                    foreach ($form->all()['cpsSecondaire']->getData() as $cpsSec) {
                        $habiExiste = $this->getEmHabil()->findOneBy(['idProfil' => $profil_cpsSe, 'idPersonne' => $cpsSec]);
                        if (!empty($habiExiste)) { // $abilitaion appel existe pas
                            $habiExiste->AddIdComite($Comite);
                            $this->em->persist($habiExiste);
                        } else {
                            $this->inserToHabilitation($cpsSec, $profil_cpsSe, $Comite);
                        }
                    }
                }

                /////////////////// Remplir mc ces et avis possible  pour test , -- Provisoir --//////////////
                $insertData = new InsertDataProvisional($this->em);
                $insertData->DataComite($Comite);
                //////////////////////////////////////////////// FIn //////////////////////////////////////////////

                $this->em->flush();
            } catch (Exception $e) {
                $this->addFlash('error', "Le comité n'a pas été créé");
            }
            $this->addFlash('success', 'Le comité '.$Comite->getLbAcr().' a bien été créé'); // un message flash pour confirmer la création du comité

            return $this->redirectToRoute('list_comite'); // redirection vers liste des comites
        }

        return $this->render('comite/gestioncomite/new.html.twig', [
            'tg_comite' => $Comite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idComite}", name="comite_show", methods={"GET"})
     * @IsGranted("ROLE_ANR")
     */
    public function show(int $idComite): Response
    {
        $tgComite = $this->getEmComite()->find($idComite);
        if (!$tgComite) {
            throw $this->createNotFoundException('Aucun comité trouvé');
        }

        return $this->render('comite/gestioncomite/show.html.twig', [
            'tg_comite' => $tgComite,
        ]);
    }

    /**
     * @throws Exception
     * @Route("/{idComite}/edit", name="comite_edit",  methods={"GET","POST"})
     * @IsGranted("ROLE_PILOTE")
     */
    public function edit(Request $request, TgComite $tgComite): Response
    {
        $this->appelClos($this->appel); // retour 403 / Accès refusé si appel est clos

        $form = $this->createForm(ComiteType::class, $tgComite,
        [
            'appel' => $this->session->get('appel'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sollicitation = $this->em->getRepository(TrEtatSol::class)->find(3);

            $profil_pres = $this->getEmProfil(4); //  président
            $profil_cpsP = $this->getEmProfil(6); // correspond à cps Prin
            $profil_cpsSe = $this->getEmProfil(7); //  cps Sec
//            $appel = $this->getEmAppPro()->find($this->session->get('appel'));
            $phase = $this->appel->getNiveauEnCours()->getIdPhase();

            try {
                //Président

                $profilComitePres = $this->getEmHabil()->findProfilByComite($tgComite, $profil_pres);
                $personneAncien = ($profilComitePres) ? $profilComitePres[0]->getIdPersonne() : null;
                $presNv = $this->getEmHabil()->findOneBy(['idProfil' => $profil_pres, 'idPersonne' => $form->all()['president']->getData()]);
                // Participation
                $particiPres = $this->getEmPartic()
                    ->findOneBy(['idPersonne' => $personneAncien, 'idComite' => $tgComite, 'idProfil' => $profil_pres]);
                if (empty($particiPres) && null !== $form->all()['president']->getData()) {
                    $this->inserToParticipation($form->all()['president']
                        ->getData(), $tgComite, $phase->getIdPhaseRef(), $sollicitation, $profil_pres);
                } elseif (!empty($particiPres) && $personneAncien !== $form->all()['president']->getData()
                    && !empty($form->all()['president']->getData())) {
                    $particiPres->setIdPersonne($form->all()['president']->getData());
                    $this->em->persist($particiPres);
                } elseif (!empty($particiPres) && null === $form->all()['president']->getData()) {
                    $this->em->remove($particiPres);
                } // fin participation

                if ((!empty($profilComitePres) && $personneAncien !== $form->all()['president']->getData()) || empty($profilComitePres)) {
                    if (!isset($presNv)) {
                        if (null !== $form->all()['president']->getData()) {
                            $this->inserToHabilitation($form->all()['president']->getData(), $profil_pres, $tgComite);
                        }
                    } else {
                        $presNv->AddIdComite($tgComite);
                        $this->em->persist($presNv);
                    }
                    if (!empty($profilComitePres) && $personneAncien !== $form->all()['president']->getData()) {
                        $profilComitePres[0]->removeIdComite($tgComite);
                        $this->em->persist($profilComitePres[0]);
                    }
                }
                //Cps Principal
                $profilComiteCpsP = $this->getEmHabil()->findProfilByComite($tgComite, $profil_cpsP);
                foreach ($profilComiteCpsP as $habilCpsP) {
                    $habilCpsP->removeIdcomite($tgComite);
                    $this->em->persist($habilCpsP);
                }
                $cpsSDatas = $form->all()['cpsprincipal']->getData();
//                $nbCpspData = count($cpsSDatas);
                foreach ($cpsSDatas as $cpsPData) {
                    $CpsPNv = $this->getEmHabil()->findOneBy(['idProfil' => $profil_cpsP, 'idPersonne' => $cpsPData]);
                    if (!isset($CpsPNv)) {
                        $this->inserToHabilitation($cpsPData, $profil_cpsP, $tgComite);
                    } else {
                        $CpsPNv->AddIdComite($tgComite);
                        $this->em->persist($CpsPNv);
                    }
                }

                //Cps Secondaire
                $profilComiteCpsSe = $this->getEmHabil()->findProfilByComite($tgComite, $profil_cpsSe);
                if (isset($profilComiteCpsSe)) {
                    foreach ($profilComiteCpsSe as $habilCpsSe) {
                        $habilCpsSe->removeIdcomite($tgComite);
                        $this->em->persist($habilCpsSe);
                    }
                }
                $cpsSDatas = $form->all()['cpsSecondaire']->getData();
                $nbCpssData = count($cpsSDatas);
                if ($nbCpssData > 0) {
                    foreach ($cpsSDatas as $cpsSData) {
                        $CpsSeNv = $this->getEmHabil()->findOneBy(['idProfil' => $profil_cpsSe, 'idPersonne' => $cpsSData]);
                        if (!isset($CpsSeNv)) {
                            $this->inserToHabilitation($cpsSData, $profil_cpsSe, $tgComite);
                        } else {
                            $CpsSeNv->AddIdComite($tgComite);
                            $this->em->persist($CpsSeNv);
                        }
                    }
                }
                $this->em->flush();
            } catch (Exception $e) {
                $this->addFlash('error', "Le comité n'a pas été modifier");
            }
            // un message flash pour confirmer la modification du comité
            $this->addFlash('success', 'Le comité a bien été modifié');

            return $this->redirectToRoute('list_comite', [
                'idComite' => $tgComite->getIdComite(),
            ]);
        }

        return $this->render('comite/gestioncomite/edit.html.twig', [//            'partici' => $partici,
            'tg_comite' => $tgComite,
            'form' => $form->createView(), ]);
    }

    /**
     * @throws Exception
     * @Route("/{idComite}", name="comite_delete")
     * @IsGranted("ROLE_PILOTE")
     */
    public function delete(Request $request, TgComite $tgComite): Response
    {
        $this->appelClos($this->appel); // retour 403 / Accès refusé si appel est clos
        if ($this->isCsrfTokenValid('delete'.$tgComite->getIdComite(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $tgComite->setBlActif(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comite_index');
    }

    /**
     * @Route("/{idParticipation}/edit", name="participation_modif", methods={"GET","POST"})
     */
    public function modifParticipation(Request $request, TgParticipation $tgParticipation): Response
    {
        $form = $this->createForm(ParticipationType::class, $tgParticipation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comite_index');
        }

        return $this->render('tg_participation/edit.html.twig', [
            'tg_participation' => $tgParticipation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $acronyme
     *
     * @return int
     *             vérifier l'unicité de l'acronyme Pour l'appel
     */
    private function similaireAcronComite($acronyme): int
    {
        $comites = $this->getEmComite()->findBy(['idAppel' => $this->appel, 'blActif' => 1]);
        $compte = 0;
        foreach ($comites as $comite) {
            similar_text(strtoupper($comite->getLbAcr()), strtoupper($acronyme), $percent);
            if (100.0 === $percent) {
                ++$compte;
            }
        }

        return $compte;
    }

    /**
     * @param $personne
     * @param $profil
     * @param $comite
     *
     * @throws Exception
     */
    private function inserToHabilitation($personne, $profil, $comite): void
    {
        $habilitation = new TgHabilitation();
        $habilitation
            ->setIdPersonne($personne)
            ->setIdProfil($profil)
            ->AddIdComite($comite)
            ->setDhMaj(new DateTime())
            ->setLbRespMaj($this->getUser()->getIdPersonne()->getLbNomUsage().' '.$this->getUser()->getIdPersonne()->getLbPrenom());
        $this->em->persist($habilitation);
    }

    /**
     * @param $personne
     * @param $comite
     * @param $phaseref
     * @param $sollicitation
     * @param $profil
     */
    private function inserToParticipation($personne, $comite, $phaseref, $sollicitation, $profil): void
    {
        $participation = new TgParticipation();
        $participation->setIdPersonne($personne)
            ->setIdComite($comite)
            ->setIdPhaseRef($phaseref)
            ->setCdEtatSollicitation($sollicitation)
            ->setBlSupprime(1)
            ->setIdProfil($profil);
        $this->em->persist($participation);
    }
}
