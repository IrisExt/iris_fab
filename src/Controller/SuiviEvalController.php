<?php


namespace App\Controller;


use App\Entity\FtCommandeApp;
use App\Entity\TgAffectation;
use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgMcCes;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Entity\TgResume;
use App\Entity\TrProfil;
use App\Entity\TrStsEvaluation;
use App\Entity\TrStsSollicitation;
use App\Entity\TrTypeCommande;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SuiviEvalController
 * @package App\Controller
 * @Route("suiviEval")
 */
class SuiviEvalController extends BaseController
{

    public function __construct()
    {
        $this->sollicColor =[
            'SOM'=> [ 'SOM', 'color' =>  '#99ccff','ordre' =>  1], // Soumis 1
            'ENC' =>[ 'ENC', 'color' =>  '#ccffff','ordre' =>  2], // En cours 2
            'ACC' =>[ 'ACC', 'color' =>  '#99ff99','ordre' =>  6], // accord Accepté 2
            'SOL' =>[ 'SOL', 'color' =>  '#ffff99','ordre' =>  3], // sollicité 3
            'AFR' =>[ 'AFR', 'color' =>  '#ccffff','ordre' =>  4], // A faire
            'REF' =>[ 'REF', 'color' =>  '#ff9999','ordre' =>  4], // Refusé 4
            'RET' =>[ 'RET', 'color' =>  '#ffcc99','ordre' =>  4], //retiré 4
            'CON' =>[ 'CON', 'color' =>  '#ff9999','ordre' =>  5], // Conflit 5
            'PRO'=> [ 'PRO', 'color' =>  '#fffff','ordre' =>  7], // Proposé
        ];
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/suivi-eval/{idComite}", name="suivi_eval_cmte")
     * tableau central
     *
     */
    public function shwoTable(Request $request, TgComite $idComite){

        if(!$this->isGranted('ROLE_CPS_P'))
            foreach ($this->emRep(TgHabilitation::class)->profilByComite($this->getUserConnect(), $idComite) as $cmt) {
                $tgprojet[] = $cmt->getIdComite();
            }
        else $tgprojet = $this->emRep(TgProjet::class)->findBy(['idComite' => $idComite]) ;


        $affects = $this->emRep(TgAffectation::class)->affectationRL($idComite);
        $datas = $this->dataSuivi($tgprojet,$affects);
//        $tgprojet =

//        dd($datas['evalColor']);
        return $this->render('suivi_evaluation/table.html.twig',[
            'comite' => $idComite,
            'affect' => $affects,
            'tgprojet' =>$tgprojet,
            'rappo' => $datas['rapporteurs'],
            'lecteur' => $datas['lecteurs'],
            'evalColors' => $datas['evalColor']
        ]);
    }

    /**
     * @param int $idProj
     * @param string $item
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/eval_proj/{idProj}" , name="eval_proj")
     * iframe evaluation
     */
    public function evalProjet(Request $request, int $idProj, $item = 'Evaluation'){

//       $sollic = ['SOL', 'RET', 'REF','CON','ACC','PRO'];
        $sollics = $this->emRep(TrStsSollicitation::class)->findAll();
        $proj =  $this->emRep(TgProjet::class)->findOneBy(['idProjet' => $idProj]);
        $affects = $this->emRep(TgAffectation::class)->affectationExpComite($idProj, $sollics);
        $ftCommandes = $this->emRep(FtCommandeApp::class)->findBy(['idProjet' => $idProj]);



        return $this->render('suivi_evaluation/evaluation_table.html.twig',
            [
                'item' => $item,
                'proj' => $proj,
                'affects' => $affects,
                'ftCommmands' => $ftCommandes,
                'evalColors' => $this->sollicColor
            ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/histo/projet/{idProjet}", name="histo_proj")
     */
    public function histoProjet(TgProjet $idProjet){

        return $this->render('suivi_evaluation/modal/histo_projet.html.twig',[
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/ajout/exper", name="add_exper")
     */
    public function ajoutExpere(){

        return $this->render('suivi_evaluation/modal/ajouter_expere.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/comment/eval/{idProjet}/{idPersonne}", name="comment_eval")
     * commentaires tableau evaluation
     */
    public function commentEval(Request $request, TgProjet $idprojet, TgPersonne $idPersonne){

        if($request->isMethod('POST')){
            $cdCmd = $this->emRep(TrTypeCommande::class)->find('COM');
            try{
                $commande = new FtCommandeApp();
                $commande->setIdProjet($idprojet)
                    ->setIdPersonne($idPersonne)
                    ->setCdCommande($cdCmd)
                    ->setTxtCommentaire($request->request->get('commentaire'));
                $this->getEm()->persist($commande);
                $this->getEm()->flush();

            }catch (\http\Exception $e) {
                $e->getMessage();
            }
            return $this->redirectToRoute('eval_proj',['idProj' => $idprojet->getIdProjet()]);
        };

        return $this->render('suivi_evaluation/modal/comment_eval.html.twig',[
            'idPersonne' => $idPersonne->getIdPersonne(),
            'idProjet' => $idprojet->getIdProjet()
        ]);
    }

    /**
     * @param TgProjet $projet
     * @param TgPersonne $personne
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/gestion-eval/{projet}/{personne}", name="gestion_eval")
     */
    public function gestionEvaluation(Request $request, TgProjet $projet, TgPersonne $personne){
        $trSollicitation = $this->emRep(TrStsSollicitation::class)->findBy([],['actionSollicitation' => 'DESC']);

        if($request->isMethod('POST')){
            $reponse = $request->request->get('act_sollicitation');
            $commentaire = $request->request->get('commentaire');
            $trSollic = $this->emRep(TrStsSollicitation::class)->findOneBy(['actionSollicitation'=>$reponse]);
            $aFaire = $this->emRep(TrStsEvaluation::class)->find('AFR');
            $enCours = $this->emRep(TrStsEvaluation::class)->find('ENC');
            // changement de l'etat de sts evaluation si conflit 'CON' ou refus 'REF' Retiré 'RET'
            $trEval = ($trSollic->getCdSollicitation() == "REF" OR  $trSollic->getCdSollicitation() == "CON" OR $trSollic->getCdSollicitation() == "RET") ? null :
                ($trSollic->getCdSollicitation() == "SOL" ? $aFaire : $enCours) ;


            try{
                $tgAffec = $this->emRep(TgAffectation::class)->findOneBy(['idPersonne'=>$personne, 'idProjet' => $projet]);
                $tgAffec->setCdSollicitation($trSollic);
                $tgAffec->setCdStsEvaluation($trEval);
                $this->getEm()->persist($tgAffec);
                $this->getEm()->flush();

            }catch (\Exception $e) {
                $e->getMessage();
            }
            return $this->redirectToRoute('eval_proj',['idProj' => $projet->getIdProjet()]);
        };

        return $this->render('suivi_evaluation/modal/gestion_eval.html.twig',[
            'actions' => $trSollicitation,
            'idPersonne' => $personne->getIdPersonne(),
            'idProjet' => $projet->getIdProjet()
        ]);
    }

    /**
     * @Route("/sendMail-eval/{projet}/{personne}", name="sollic_mail_eval")
     * mail générique ??
     */
    public function sendMailEval(Request $request, TgProjet $projet, TgPersonne $personne){
        $emailPersonne = $this->emRep(User::class)->findOneBy(['idPersonne' => $personne]);
//        dd($emailPersonne);
        if($request->isMethod('POST')){
            try{

            }catch (\Exception $e) {
                $e->getMessage();
            }
            return $this->redirectToRoute('eval_proj',['idProj' => $projet->getIdProjet()]);
        }
        return $this->render('suivi_evaluation/modal/send_mail_eval_confirmation.html.twig',[
            'mail' => $emailPersonne,
            'idPersonne' => $personne,
            'idProjet' => $projet->getIdProjet()
        ]);

    }


    public function bookletSuiviEval(Request $request, TgProjet $tgProjet){

        $resumes = $this->emRep(TgResume::class)->findBy(['idProjet' => $tgProjet]) ??[];
        $tgMotCleCes = $this->emRep(TgMcCes::class)->findBy(['idComite' => $tgProjet->getIdComite()]) ??[];

        return $this->render('suivi_evaluation/booklet.html.twig',[
            'projet' => $tgProjet,
            'resumes' => $resumes,
            'tgMotCleCes' => $tgMotCleCes,
            'requests' => $request
        ]);
    }

    /**
     * @param $tgprojet
     * @param $affects
     * @return array
     * retourne les lecteurs rapporteur et les experts d'un comité (nom et couleur des sollicitation )
     */
    private function dataSuivi($tgprojet, $affects){
        $evalColor = $lecteurs = $rapporteurs = null;
        foreach ($tgprojet as $proj){
            foreach ($affects as $affect ){
                if($affect->getIdProjet() == $proj){
                    if(!empty($affect->getCdSollicitation())){
                        $cdEval = !empty($affect->getCdStsEvaluation())? $affect->getCdStsEvaluation() : null;
                        array_push($this->sollicColor[$affect->getCdSollicitation()->getCdSollicitation()], $affect->getIdPersonne());
                        array_push($this->sollicColor[$affect->getCdSollicitation()->getCdSollicitation()], $cdEval);
                        $evalColor[$proj->getIdProjet()][] = $this->sollicColor[$affect->getCdSollicitation()->getCdSollicitation()];
                    }
                    if(!empty($affect->getIdStAffect())){
                        if($affect->getIdStAffect()->getIdStAffect() == 1){
                            $rapporteurs[$proj->getIdProjet()][] = $affect->getIdPersonne();
                        }
                        if($affect->getIdStAffect()->getIdStAffect() == 2){
                            $lecteurs[$proj->getIdProjet()][] = $affect->getIdPersonne();
                        }
                    }
                }
            }
        }
        return ['evalColor'=> $evalColor, 'rapporteurs' => $rapporteurs,'lecteurs'=> $lecteurs];
    }

}