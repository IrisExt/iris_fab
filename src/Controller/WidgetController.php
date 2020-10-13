<?php


namespace App\Controller;


use App\Entity\TgAppelProj;
use App\Entity\TgBloc;
use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgNiveauPhase;
use App\Entity\TgPartenariat;
use App\Entity\TgProjet;
use App\Entity\TrProfil;
use App\Repository\TgProjetRepository;
use App\Repository\TlFormulairAppelRepository;
use App\Security\AffectationRole;
use App\Security\AppelExiste;
use App\Service\HabilitationService;
use http\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class WidgetController extends BaseController
{
    /**
     * @var AppelExiste
     */
    private $appelExiste;
    /**
     * @var AffectationRole|null
     */
    private $affectationRole;
    /**
     * @var HabilitationService
     */
    private $habilitationService;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * WidgetController constructor.
     * @param AppelExiste $appelExiste
     * @param AffectationRole|null $affectationRole
     */
    public function __construct(AppelExiste $appelExiste, SessionInterface $session, AffectationRole $affectationRole = null )
    {

        $this->appelExiste = $appelExiste;
        $this->affectationRole = $affectationRole;
        $this->session = $session;
    }

    public function widgetDosEm(){

//        $porteurPProfil = $this->habilitationsPersonne($this->profilEntity(10));

            $appelEnCours = $this->getEmAppPro()->findBy([], ['idAppel' => 'DESC']);
            $phaseEnCours = $appelEnCours[0]->getNiveauEnCours();

            return $this->render('widgets/dosem.html.twig',[
                'appels'=> $appelEnCours
            ]);
    }

    /**
     * @return Response
     */
    public function widgetCpsP(){
        $cpsPProfil = $this->habilitationsPersonne($this->profilEntity(6));
        return $this->render('widgets/cpsp.html.twig',[
            'habilitations' => $cpsPProfil
        ]);
    }

    public function widgetCpsS(){
        $cpsSProfil = $this->habilitationsPersonne($this->profilEntity(7));
        return $this->render('widgets/cpsS.html.twig',[
            'habilitations' => $cpsSProfil
        ]);
    }

    public function widgetPilote(){
        $piloteProfil = $this->habilitationsPersonne($this->profilEntity(1));
        return $this->render('widgets/pilote.html.twig',[
            'habilitations' => $piloteProfil
        ]);
    }

    public function widgetPA(){
        $prComite = $this->habilitationsPersonne($this->profilEntity(4));
        return $this->render('widgets/president_comite.html.twig',[
            'habilitations' => $prComite
        ]);
    }

    /**
     * @return Response
     */
    public function widgetPres(){
        $cpsPProfil = $this->habilitationsPersonne($this->profilEntity(4));
        return $this->render('widgets/pres.html.twig',[
            'habilitations' => $cpsPProfil
        ]);
    }


    /**
     * @return Response
     * @Route("/hab_user")
     */
    public function widgetAffectationRLComite(){
        $habComites = $this->emRep(TgHabilitation::class)->userhabilitationComite($this->getUserConnect());
        $comitesPersonne =  $this->comiteHabi($habComites,[1]); // niveau soumission 1 ,evaluation 2 mais pas publication [3]

        $profil = !empty($habComites) ? $habComites[0]->getIdProfil(): null;
        return
            $this->render('widgets/affectation_R_L_comite.html.twig', [
                'comites' => $comitesPersonne,
                'profil' => $profil,
            ]);
    }

    /**
     * @return Response
     * @Route("/mmbre")
     */
    public function widgetAffectationRLMembre(){
        $habils = $this->habilitationsPersonne($this->profilEntity(9)); // findOneBy
        $profil = $habils->getIdProfil();
        $comitesPersonne =[];
           foreach ($habils->getIdComite() as $comite_p) {
               $this->APstatut($comite_p->getIdAppel()) ? $comitesPersonne[] = $comite_p : null;
           }
        return $this->render('widgets/affectation_R_L_membre.html.twig',[
            'comites' => $comitesPersonne,
            'profil' => $profil,
        ]);
    }
    /**
     * @return Response
     * @Route("/app", name="app")
     * retourne les hailitations pour chaque appel
     */
    public function widgetAppel(){
        $habils = $this->emRep(TgHabilitation::class)->userhabilitationAppel($this->getUserConnect());
        $AppelHabPersonne = $this->appelHabi($habils);
        return $this->render('widgets/appelform.html.twig', [
            'appels' => $AppelHabPersonne,
        ]);
    }

    public function widgetPorteurP(TlFormulairAppelRepository $tlFormulairAppelRepository, TgProjetRepository $tgProjetRepository)
    {


        $personne = $this->getUserConnect();
        if(!$personne){
            throw $this->createNotFoundException('Expired');
        }
        $habit = $this->habilitationsPersonne($this->profilEntity(15));
        $soumAppel = null; $poursuiSoumAppel=null;$idBloc=null;$widgetSee = false;
        if($habit) {
            $trProfil = $this->getDoctrine()->getRepository(TrProfil::class)->findOneBy(['idProfil' => 15]);
            // appel sans soumission du porteur
            $appels= $this->emRep(TgAppelProj::class)->AppelWithNiveau(1); // les appels en phase 1

            $soumAppel =   $tlFormulairAppelRepository->AppelWithSoumission($appels);
            if(!empty($soumAppel)){
                foreach ($soumAppel as $soumAapg){
                $niveau =  $soumAapg->getIdAppel()->getNiveauEnCours()->getIdPhase()->getIdPhaseRef()->getIdPhaseRef() == 1 ; // return true phase 1 soumission affichage widget
                $widgetSee = $niveau == true ;
                }
            };
            $poursuiSoumAppel =  $tgProjetRepository->AppelWithSoumPersonne($personne,1);
            $lbRole = ($trProfil)? $trProfil->getLbRole(): null;
            $this->affectationRole->changeRole($lbRole);
            if(15 === $trProfil->getIdProfil()) $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlInstFiType'])->getIdBloc() ?: null;
            else $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlPartenariatType'])->getIdBloc() ?: null;
        }

        return
            $this->render('widgets/porteurprojet.html.twig', [
                'soumAppels' => $soumAppel,
                'poursuiSoumAppels' => $poursuiSoumAppel,
                'bloc' => $idBloc,
                'widgetSee' => $widgetSee
            ]);
    }

    /**
     * @Route("/accueil/{idAppel}/{idNiveauphase}/{idProfil}/{idComite}/{redirect}", name="profil_user")
     * mettre en session l'appel, niveau phase, phase,..
     */
    public function definirprofil(TgAppelProj $idAppel, TgNiveauPhase $idNiveauphase ,TrProfil $idProfil = null, TgComite $idComite = null, string $redirect =null )
    {

        $user = $this->getUserConnect()->getIdpersonne();
        // s'il n'a pas le ROLE DOSEM Ou Admin
        if (!$this->isGranted('ROLE_DOS_EM')) {
            if (!empty($idComite)) {
                $accesComite = $this->getEmHabil()
                    ->profilByComite($user, $idComite);
            };
            $accesAppel = $this->getEmHabil()
                ->profilByAppel($user, $idAppel);

            if(( isset($accesComite) && !empty($accesComite)) ||  (isset($accesAppel) && !empty($accesAppel)) ){
                $this->affectationRole->changeRole($idProfil->getLbRole());
            }else{
                throw new AccessDeniedException();
            }
        }
        $phase = $this->getEmPhase()->find($idNiveauphase->getIdPhase());
        $comiteratache =  ($this->getEmHabil()->userParticipComite($user)) ? $this->getEmHabil()->userParticipComite($user) : '';
        $this->session->set('appel', $idAppel);
        $this->session->set('phasencours' ,$idNiveauphase);
        $this->session->set('phase', $phase);
        $this->session->set('comites', $comiteratache);


        return $this->redirectByRole($idComite, $redirect);

    }

    /**
     * @param $formulaire
     * @return Response
     * @Route("/test")
     */
    public function widgetformSoumissionRespSc(TlFormulairAppelRepository $tlFormulairAppelRepository)
    {
        $personne = $this->getUserConnect();
        $habitRsci = $this->getDoctrine()->getRepository(TgHabilitation::class)->findOneBy(['idProfil' => 12, 'idPersonne' => $personne]);
        $soumAppel = null; $poursuiSoumAppel=null;
        $lbProfil ='Responsable scientifique';
        if($habitRsci) {
            $trProfil = $this->getDoctrine()->getRepository(TrProfil::class)->findOneBy(['idProfil' => 12]) ? : null;
            $lbRole = ($trProfil)? $trProfil->getLbRole(): null;
            $lbProfil = ($trProfil)? $trProfil->getLbProfil(): null;
//            $this->affectationRole->changeRole($lbRole);
            $tgPartenaires = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['respScient' => $personne]) ?: null;
            $seeWidget = array();
            foreach ($tgPartenaires as $tgPartenaire) {
                $lbPartenaire =  $tgPartenaire->getHebergeur() ? : $tgPartenaire->getTutGest();
                $soumAppel[$tgPartenaire->getIdPartenaire()]['partenaire'] = ($lbPartenaire)? $lbPartenaire->getLbNomFr() : null;
                // appel sans soumission du porteur
                $soumAppel[$tgPartenaire->getIdPartenaire()]['soumission'] = $seeWidget[] = ($tgPartenaire->getIdProjet()) ? $tlFormulairAppelRepository->findFormBlocSoumission($personne, true, $tgPartenaire->getIdProjet()->getIdAppel()): null;
            }

        }
        $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlPartenariatType'])->getIdBloc() ?: null;

        return
            $this->render('widgets/respSc.html.twig', [
                'soumAppels' => $soumAppel,
                'seeWidget' => $seeWidget,
                'idBloc' => $idBloc,
                'profil' => $lbProfil,
            ]);
    }

    /**
     * @param $formulaire
     * @return Response
     */
    public function widgetformSoumissionRespAdm(TlFormulairAppelRepository $tlFormulairAppelRepository)
    {
        $personne = $this->getUserConnect();
        $habitRadm = $this->getDoctrine()->getRepository(TgHabilitation::class)->findOneBy(['idProfil' => 18, 'idPersonne' => $personne]);
        $soumAppel = null; $poursuiSoumAppel=null;
        $lbProfil = 'Responsable administratif';
        if($habitRadm) {
            $trProfil = $this->getDoctrine()->getRepository(TrProfil::class)->findOneBy(['idProfil' => 18]) ? : null;
            $lbRole = ($trProfil)? $trProfil->getLbRole(): null;
            $lbProfil = ($trProfil)? $trProfil->getLbProfil(): null;
            // $this->affectationRole->changeRole($lbRole);
            $tgPartenaires = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['gestAdm' => $personne]) ?: [];
            $seeWidget = array();
            foreach ($tgPartenaires as $tgPartenaire) {
                $lbPartenaire =  $tgPartenaire->getHebergeur() ? : $tgPartenaire->getTutGest();
                $soumAppel[$tgPartenaire->getIdPartenaire()]['partenaire'] = ($lbPartenaire)? $lbPartenaire->getLbNomFr() : null;
                // appel sans soumission du porteur
                $soumAppel[$tgPartenaire->getIdPartenaire()]['soumission'] = $seeWidget[] = ($tgPartenaire->getIdProjet()) ? $tlFormulairAppelRepository->findFormBlocSoumission($personne, true, $tgPartenaire->getIdProjet()->getIdAppel()): null;
            }
        }
        $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlPartenariatType'])->getIdBloc() ?: null;
        return
            $this->render('widgets/respAdm.html.twig', [
                'soumAppels' => $soumAppel,
                'idBloc' => $idBloc,
                'profil' => $lbProfil,
                'seeWidget' => $seeWidget,
            ]);
    }

    /**
     * @Route("/soumissionwithrole/{tgAppelProj}/{tgFormulaire}/{profil}/{idPartenaire}", name="soumission_with_role")
     * @param int $tgAppelProj
     * @param int $tgFormulaire
     * @param int $profil
     * @param null $idPartenaire
     * @param null $idProjet
     * @return Response
     */
    public function getSoumissionWithRole(int $tgAppelProj, int $tgFormulaire, int $profil, $idPartenaire= null)
    {
        $trProfil = $this->getDoctrine()->getRepository(TrProfil::class)->findOneBy(['idProfil' => $profil]) ? : null;
        $lbRole = ($trProfil)? $trProfil->getLbRole(): null;
        $this->affectationRole->changeRole($lbRole);
        if(15 === $profil) $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlInstFiType'])->getIdBloc() ?: null;
        else $idBloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneby(['className' => 'BlPartenariatType'])->getIdBloc() ?: null;
        $tgPartenaire = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneBy(['idPartenaire' => $idPartenaire])?  : '';
        $idProjet = ($tgPartenaire)? $tgPartenaire->getIdProjet()->getIdProjet() : '';
        $response = new Response(json_encode(array(
            'appel' => $tgAppelProj,
            'formulaire' => $tgFormulaire,
            'bloc' => $idBloc,
            'idPartenaire' => $idPartenaire,
            'idProjet' => $idProjet
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param $habilitation
     * @return array
     * retourne comités/niveau
     */
    private function comiteHabi($habilitation, $niveauPhases = []) : array
    {
        $comitesPersonne =[];
        foreach ($habilitation as $habComite){
            foreach($habComite->getIdComite() as $comite_p){
                $nivPhase = !empty($niveauPhases)
                  ? in_array($comite_p->getIdAppel()->getNiveauEnCours()->getIdTypeNiveu()->getIdTypeNiveu(), $niveauPhases): false;

                (!$nivPhase && empty($niveauPhases)) || ($nivPhase && !empty($niveauPhases)) ?
                    $this->APstatut($comite_p->getIdAppel()) ?  $comitesPersonne[] = $comite_p : null
                    : null;
            }

        };

        return $comitesPersonne;
    }

    /**
     * @param $habilitation
     * @return array
     * retourne les appels ou la personne est affecté /niveau
     */
    private function appelHabi($habilitation, $niveauPhases = []) :array
    {
        $appelHabPersonne =[];
        foreach ($habilitation as $habAppel){
            foreach($habAppel->getidAppel() as $appel_p){
                $nivPhase = !empty($niveauPhases)
                    ? in_array($appel_p->getNiveauEnCours()->getIdTypeNiveu()->getIdTypeNiveu(), $niveauPhases): false;
                (!$nivPhase && empty($niveauPhases)) ||  ($nivPhase && !empty($niveauPhases)) ? $this->APstatut($appel_p->getIdAppel()) ?  $appelHabPersonne[] = $appel_p : null
                    : null;
            }
        };
        return $appelHabPersonne;
    }

}