<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AffectationRole;
use App\Security\AppelExiste;
use App\Service\HabilitationService;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccueilController.
 */
class AccueilController extends BaseController
{
    /**
     * @var AffectationRole|null
     */
    private $affectationRole;

    /**
     * @var AppelExiste
     */
    private $appelExiste;
    /**
     * @var HabilitationService
     */
    private $habService;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * AccueilController constructor.
     * @param AppelExiste $appelExiste
     * @param HabilitationService $habService
     * @param SessionInterface $session
     * @param AffectationRole|null $affectationRole
     */
    public function __construct(AppelExiste $appelExiste, HabilitationService $habService, SessionInterface $session, AffectationRole $affectationRole = null)
    {
        $this->appelExiste = $appelExiste;
        $this->affectationRole = $affectationRole;
        $this->habService = $habService;
        $this->session = $session;
    }

    /**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {
        if (false === $this->appelExiste->index()) {
            $this->addFlash('infos', 'Veuillez créer un appel à projets.');

            return $this->redirectToRoute('tg_appel_proj_new');
        }
        $temp = $this->habService->habilitationUserWidget();

        //dd($temp);
        if (empty($temp)) {
            $this->addFlash('error', 'Vous avez aucune habilitation ou le Compte n\'est pas actif, Veuillez contacter l\'administrateur');

            return $this->render('accesnonautorisé.html.twig');
        }

        return $this->render('accueil_widget.html.twig', [
            'widgets' => $temp,
        ]);
    }

    /**
     * Affichage de l'aapg actuel et le choix à sélectionner dans le menu.
     *
     * @Route("/liste/listAapg", name="list_aapgs", methods={"GET"})
     */
    public function listAapg(): Response
    {
        $this->isGranted('ROLE_DOS_EM') ? $dosem = true : $dosem = false; // pour affichage dans le menu des appels et phase , DOSEM pas d'appel; pas de phase

        $user = $this->getUserConnect();

        $appelEnCours = $this->getEmAppPro()->findAllAppelEnCours();

        $lstappel = $this->getEmHabil()->habilitationAllProfil($user->getIdPersonne());

        $aapgActuel = $this->getEmAppPro()->findOneBy(['idAppel' => $this->session->get('appel')]);

        return $this->render('appelaprojet.html.twig', [
            'appelEnCours' => $appelEnCours,
            'dosem' => $dosem,
            'aapgs' => $lstappel,
            'aapgActeul' => $aapgActuel,
        ]);
    }

    /**
     * @param $appel
     *
     * @return mixed
     *               retoure True ou False, false appel est clos
     */
    public function statutAppel($appel): Response
    {
        $statut = $this->getEmAppPro()->findDateClosAppel($appel);
        $statutAP = empty($statut) ? false : true;

        return
            $this->render('appelprojet/statutappel.html.twig', ['statut' => $statutAP]);
    }

    public function widgetProfilPersonne()
    {
        $habilProfil = $this->getEmHabil()->habilitationAllProfil($this->getUserConnect());

        return $this->render('widgets/profilPersAppelAndComite.html.twig', [
            'habilProfils' => $habilProfil,
        ]);
    }
}
