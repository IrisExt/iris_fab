<?php


namespace App\Controller;


use App\Entity\TgComite;
use App\Entity\TgPhase;
use App\Entity\TgReunion;
use App\Entity\TgSeance;
use App\Entity\TrTypeReunion;
use App\Form\ReunionType;
use DateTime;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReunionController
 * @package App\Controller
 * @Route("reun")
 */
class ReunionController extends BaseController
{

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/showReunion" , name="show_periode_reu")
     */
    public function showPeriodeReunion(Request $request){

        $reunion_recup = $this->getEm()->getRepository(TgReunion::class)->findBy(['idAppel' =>  $this->session->get('appel'), 'blActif' => 1], ['idReunion' => 'DESC']);
        $count_reun = count($reunion_recup);
        return $this->render('reunion/showreunion.html.twig', [
            'count_reun' => $count_reun,
            'reunions' => $reunion_recup
        ]);
    }

    /**
     * le pilote ajoute une periode des reunions
     * @Route("/ajouterPeriodeReu" , name="ajouter_periode_reu")
     */
    public function ajouterPeriodeReunion(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reunion = new TgReunion();
        $phase = $this->getEmPhase()->find($this->session->get('phase'));
        $form = $this->createForm(ReunionType::class, $reunion,
            [
                'appel' =>  $this->session->get('appel'),
            ]);

        $form->handleRequest($request);
        return $this->render('reunion/modalPeriodeReunion/ajouterperiodereunion.html.twig', [
            'pahse' => $phase,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/modifreunion/{idReunion}", name ="modif_reunion")
     */
    public function modifreunion(Request $request, TgReunion $reunion)
    {

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ReunionType::class, $reunion,
            [
                'appel' =>  $this->session->get('appel'),
            ]);
        $form->handleRequest($request);
        $idReunion = $form->getData()->getIdReunion();
        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $seances = $em->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion]);
            if (!empty($seances)) {
                foreach ($seances as $seance) {
                   $verif =  $this->diffDureMaxByReunionSeance($reunion, $seance->getIdComite(), $data->getNbDureeMax());
                    if($verif == true){
//                        $this->addFlash('error', 'Impossible de modifier la durée max. ,une séance d\'un comité est hors cette durée. ');
                        return $this->redirect('/fr/comite/list?reun-true=msg3reu');
                    };
                    if ($seance->getDtSeance() < $form->getData()->getDtDebPeriode() || $seance->getDtSeance() > $form->getData()->getDtFinPeriode()) {
//                        $this->addFlash('error', 'Impossible de modifier la période autorisé, une séance d\'un comité est hors périodes ');
                        return $this->redirect('/fr/comite/list?reun-true=msg4reu');
                    }
                };
            };
            $interval = DATE_DIFF($data->getDtFinPeriode(), $data->getDtDebPeriode());
            if ($interval->invert == 0) {  // diff entre les dates négatif  est 0 positif est 1
                $this->addFlash('error', 'La date début est inférieur  à la date fin !');
                return $this->redirect('/fr/comite/list?reun-true=msg5reu');
            };

            if ($interval->days - $data->getNbDureeMax() < 0) {
//                $this->addFlash('error', ' La durée max. ' . $data->getNbDureeMax() . ' Jour(s) est supérieur à la période autorisée de ' . $interval->days . ' jour(s) !');
                return $this->redirect('/fr/comite/list?reun-true=msg6reu');
            };

            $em->persist($reunion);
            $em->flush();
//            $this->addFlash('success', 'La reunion a été modifiée avec succès !');
            return $this->redirect('/fr/comite/list?reun-true=msg7reu');
        }
        if ($form->isSubmitted() && !$form->isValid()) {

            $this->addFlash('error', 'le format date est incorrecte veuillez recommencer. exemple : 23/09/2019');
            return $this->redirectToRoute('list_comite');

        }
        return $this->render('reunion/modfierreunionmodal.html.twig', [
            'form' => $form->createView(),
            'idReunion' => $idReunion
        ]);
    }

    /**
     * @param Request $request
     * @param TgReunion $reunion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{idReunion}" , name="annuler_reunion")
     */
    public function annulerReunion(Request $request, TgReunion $reunion)
    {

        if ($this->isCsrfTokenValid('annuler' . $reunion->getIdReunion(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();
            $seances = $em->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion]);
            if (empty($seances)) {
                $reunion->setBlActif(0);
                $em->persist($reunion);
                $em->flush();
//                $this->addFlash('success', 'Les reunion des comités ont bien été supprimées !');
            } else {
//                $this->addFlash('error', 'Impossible de supprimer, des seances sont déja programmées pour cette réunion');
                return $this->redirect('/fr/comite/list?reun-true=msg8reu');
            }
        }
        return $this->redirect('/fr/comite/list?reun-true=msg9reu');
    }

    public function betweenDates($Date, $startDate, $endDate)
    {
        return ($Date >= $startDate) && ($Date <= $endDate);
    }

    public function diffDureMaxByReunionSeance($reunion, $comite, $nbDureeMax, $nbseace = false)
    {
        $seances = $this->getDoctrine()->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite]);

        if (count($seances) > $nbDureeMax) {
            $am = 0;
            $pm = 0;
            foreach ($seances as $am_pm) {
                $am_pm->getMatin() ? $am++ : null;
                $am_pm->getApresMidi() ? $pm++ : null;
            };

            if ($am + $pm > $nbDureeMax * 2) {
                $nbseace = true;

            }

        };
        return $nbseace;
    }

    /**
 * @Route("/periode/addReunion", name="add_reunion")
 */
    public function addReunion(Request $request){

        $form = $request->getContent();
        $data = json_decode($form, true);

        $error = [];
        $succes='';
        $appel = $this->getEmAppPro()->find( $this->session->get('appel'));
        $reunion_recup = $this->getEm()->getRepository(TgReunion::class)->findBy(['idAppel' => $appel, 'blActif' => 1]);
        $phase = $this->getEmPhase()->find($data["reunion[idPhase]"]);
        $idTypephase = $this->getDoctrine()->getRepository(TrTypeReunion::class)->find($data["reunion[idTypeReunion]"]);
        $obligatoir = isset($data["reunion[blObligatoire]"]) ? $data["reunion[blObligatoire]"] : false;
        $debut = date('Y-m-d',strtotime(str_replace('/', '-',$data["reunion[dtDebPeriode]"])));
        $fin = date('Y-m-d',strtotime(str_replace('/', '-',$data["reunion[dtFinPeriode]"])));
        $dateDebut =  new DateTime($debut);
        $dateFin  =  new DateTime($fin);
        $interval = DATE_DIFF($dateDebut, $dateFin);
        if ($interval->days -  $data["reunion[nbDureeMax]"] < -2 ) {
            $error = 'La durée max. ' . $data["reunion[nbDureeMax]"] . ' Jour(s) est supérieur à la période autorisée de ' . $interval->days . ' jour(s) !';
        };
        $diff =  $dateDebut->diff($dateFin)->format('%R');
        if ($diff == '-') {  // diff entre les dates négatif  est 0 positif est 1
            $error ='La date début est inférieur  à la date fin !';
        };
        foreach ($reunion_recup as $verif) {
            $vphase = $verif->getIdPhase()->getIdPhase();
            $vTypeReunion = $verif->getIdTypeReunion()->getIdTypeReunion();
            $vdatdebut = $verif->getDtDebPeriode();
            $vdatfin = $verif->getDtfinPeriode();
            // vérifier réunion progrmmé avec les même critères
            if ($vphase == $data["reunion[idPhase]"] && $vTypeReunion == $data["reunion[idTypeReunion]"] && $vdatdebut == $dateDebut && $vdatfin == $dateFin) {
                $error ='Vous avez déja une réunion programmé avec ces critères';
            };
        };
        if(empty($error)){
            $reunion = new TgReunion();
            $reunion->setIdAppel($appel)
                ->setIdTypeReunion($idTypephase)
                ->setlbTitre($data["reunion[lbTitre]"])
                ->setIdPhase($phase)
                ->settxComment($data["reunion[txComment]"])
                ->setDtDebPeriode($dateDebut)
                ->setDtFinPeriode($dateFin)
                ->setNbDureeMax($data["reunion[nbDureeMax]"])
                ->setBlObligatoire($obligatoir)
                ->setBlActif(1);
            $this->getEm()->persist($reunion);
            $this->getEm()->flush();
            $succes ='La période des réunions pour tous les comités ont été créées avec succès !';
        }
        $response = new Response(json_encode(['errors' => $error,'succes' => $succes])
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}