<?php

namespace App\Controller;

use App\Entity\TgComite;
use App\Entity\TgReunion;
use App\Entity\TgSeance;
use App\Form\SeanceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * class TgSeance
 * @Route("sea")
 */
class SeanceController extends BaseController
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
     * @Route("seance", name="tg_seance")
     */
    public function index()
    {
        return $this->render('tg_seance/index.html.twig', [
            'controller_name' => 'TgSeanceController',
        ]);
    }

    /**
     * modal pour afficher les reunion + ajouter seance
     * @Route("/Seances/{idComite}" , name="show_seance")
     */
    public function show(TgComite $comite)
    {

            $em = $this->getDoctrine()->getManager();

            $reunions = $em->getRepository(TgReunion::class)->seanceByReunion($this->session->get('appel')); // reunions du comite
            $count_reun = count($reunions);

            return $this->render('tg_seance/modalSeanceComite/seance.html.twig', [
                'comite' => $comite,
                'count_reun' => $count_reun,
                'reunions' => $reunions,

            ]);

    }

    /**
     * modal ajouter une seance
     * @Route("/ajoutSeance/{idReunion}/{idComite}", name="ajouter_seance")
     */
    public function ajouterSeance(Request $request, TgReunion $reunion, TgComite $comite)
    {

        $em = $this->getDoctrine()->getManager();
        $seance = new TgSeance();
        $form = $this->createForm(SeanceType::class, $seance,
            [
                'reunion' => $reunion,
                'comite' => $comite
            ]
        );

        $seanceReunion = $em->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite]);
        $nbseance_demiJ = $this->verifSeanceReunion($seanceReunion, $reunion);

        $dateDebut = $reunion->getDtDebPeriode();
        $dateFin = $reunion->getDtfinPeriode();
        $form->handleRequest($request);
        return $this->render('tg_seance/modalSeanceComite/ajouterSeanceComite.html.twig', [
            'form' => $form->createView(),
            'idcomite' => $comite,
            'idreunion' => $reunion,
            'datedebut' => $dateDebut,
            'datefin' => $dateFin,
            'nbseance' => $nbseance_demiJ['nbseance'],
            'dureemax' => $reunion->getnbDureeMax(),
            'countSeance' => $nbseance_demiJ['countSeance'],
            'demiJ' => $nbseance_demiJ['demiJ'],
        ]);
    }

    private function verifSeanceReunion($seanceReunion, TgReunion $reunion)
    {
        $countSeance = count($seanceReunion);
        $nbseance = true;
        $demiJ = false;
        if (count($seanceReunion) >= $reunion->getnbDureeMax()) {
            $am = 0;
            $pm = 0;
            foreach ($seanceReunion as $am_pm) {
                $am_pm->getMatin() ? $am++ : null;
                $am_pm->getApresMidi() ? $pm++ : null;
            };
            if ($am + $pm >= $reunion->getnbDureeMax() * 2) { // s'il reste plus de jour return false
                $nbseance = false;
            }
            if (($reunion->getnbDureeMax() * 2) - ($am + $pm) == 1) { // s'il reste une demi journée return True
                $demiJ = true;
            };
        };
        return ['countSeance' => $countSeance, 'nbseance' => $nbseance, 'demiJ' => $demiJ];
    }

    /**
     * @param Request $request
     * @param TgReunion $reunion
     * @param TgComite $comite
     * @return Response
     * @throws \Exception
     * @Route("/ajax/addSeance/{idReunion}/{idComite}", name="add_seance")
     */
    public function addSeance(Request $request, TgReunion $reunion, TgComite $comite)
    {

        $error = [];
        $succes = '';
        $form = $request->getContent();
        $data = json_decode($form, true);
        $am = isset($data["seance[matin]"]) ? $data["seance[matin]"] : false;
        $pm = isset($data["seance[apresMidi]"]) ? $data["seance[apresMidi]"] : false;
        $seanceReunion = $this->getDoctrine()->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite]);
        $datSe = date('Y-m-d', strtotime(str_replace('/', '-', $data["seance[dtSeance]"])));
        $dateSeance = new DateTime($datSe); //convert to dateTime
        foreach ($seanceReunion as $verifSeance) { // verifier les doublons

            if (($verifSeance->getDtSeance() == $dateSeance && $verifSeance->getMatin() == $am) || ($verifSeance->getDtSeance() == $dateSeance && $verifSeance->getApresMidi() == $pm)) {
                $error = ' Impossible d\'ajouter, une réunion à déja été programmée pour cette date  !';
            }
        }

        $verifDate = $this->betweenDates($dateSeance->format('Ymd'), $reunion->getDtDebPeriode()->format('Ymd'), $reunion->getDtFinPeriode()->format('Ymd')); // retourn false s'il est hors du créneau

        $nbseance_demiJ = $this->verifSeanceReunion($seanceReunion, $reunion);

        if ($nbseance_demiJ['demiJ'] == true && $am == 1 && $pm == 1) {
            $error = "Il vous reste qu'une demi journée  !";
        }
        if ($nbseance_demiJ['nbseance'] == false) {
            $error = "vous avez atteint le nombre max. de jour  !";
        }
        if (false === $verifDate) {
            $error = 'La date est hors période !';
        }
        if (empty($error)) {
            $seance = new TgSeance();
            $seance->setIdComite($comite)
                ->setIdReunion($reunion)
                ->setDtSeance($dateSeance)
                ->setApresMidi($pm)
                ->setMatin($am);
            $this->getEm()->persist($seance);
            $this->getEm()->flush();

            $succes = 'La réunion à bien été créée pour le : ' . $seance->getDtSeance()->format('d/m/Y');

        }

        $response = new Response(json_encode(['errors' => $error, 'succes' => $succes, 'dateS' => $data["seance[dtSeance]"]])
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * modal modifier une seance
     * @Route("/modifSeance/{idSeance}", name="modifier_seance", methods={"GET","POST"}))
     */
    public function mofifierSeance(Request $request, TgSeance $seance): Response
    {

        $em = $this->getDoctrine()->getManager();
        $seanceReunion = $this->getDoctrine()->getRepository(TgSeance::class)
            ->modifSeanceDoublon($seance, $seance->getIdReunion(), $seance->getIdComite()->getIdComite());
        $form = $this->createForm(SeanceType::class, $seance,
            [
                'reunion' => $seance->getIdReunion(),
                'comite' => $seance->getIdComite()
            ]
        );
        $form->handleRequest($request);


//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $nbseance_demiJ = $this->verifSeanceReunion($seanceReunion, $seance->getIdReunion());
//
//            if($nbseance_demiJ['demiJ'] == true && $seance->getMatin() == true && $seance->getApresMidi() == true){
//                return   $this->returnPage($seance->getIdComite()->getIdComite(), 'msg3error');
//            }
//            $dateSeance = $seance->getDtSeance()->format('Ymd');
//
//            $verifDate = $this->betweenDates($dateSeance, $seance->getIdReunion()->getDtDebPeriode()->format('Ymd'), $seance->getIdReunion()->getDtFinPeriode()->format('Ymd')); // retourn false s'il est hors du créneau
//
//            foreach ($seanceReunion as $verifSeance) { // verifier les doublons
//                if (($verifSeance->getDtSeance() == $seance->getDtSeance() && $verifSeance->getMatin() == $seance->getMatin())
//                    ||
//                    ($verifSeance->getDtSeance() == $seance->getDtSeance() && $verifSeance->getApresMidi() == $seance->getApresMidi()))
//                {
//
//                   return $this->returnPage($seance->getIdComite()->getIdComite(), 'msg2error');
//                }
//
//            };
//            if (false === $verifDate) {
//                return $this->returnPage($seance->getIdComite()->getIdComite(), 'msg1error');
//            };
//
//            $this->getEm()->persist($seance);
//            $this->getEm()->flush();
//            return $this->redirect($this->generateUrl('list_comite',['cmte-seance' => $seance->getIdComite()->getIdComite(), 'msg' => 'msg2success']));
//
//        }

        return $this->render('tg_seance/modalSeanceComite/modfierseance.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/modifSeance/Ajax/{idSeance}", name="modifier_ajax_seance")
     */
    public function modifSeanceAjax(Request $request, TgSeance $idSeance)
    {
//        if($request->isXmlHttpRequest()) {
            $error = [];
            $succes = '';
            $form = $request->getContent();
            $data = json_decode($form, true);
            $checkM = isset($data["seance[matin]"]); // true ou false
            $checkAM = isset($data["seance[apresMidi]"]); // true ou false
        $seance = $this->emRep(TgSeance::class)->find($idSeance);
        $seanceReunion = $this->getDoctrine()->getRepository(TgSeance::class)
            ->modifSeanceDoublon($seance, $seance->getIdReunion(), $seance->getIdComite()->getIdComite());
        $datSe = date('Y-m-d', strtotime(str_replace('/', '-', $data["seance[dtSeance]"])));
        $dateSeance = new DateTime($datSe); //convert to dateTime
//        dd($dateSeance, $seance->getIdReunion()->getDtDebPeriode(),  $seance->getIdReunion()->getDtFinPeriode());
        $reunion =$this->emRep(TgReunion::class)->find($data["seance[idReunion]"]);
        $nbseance_demiJ = $this->verifSeanceReunion($seanceReunion, $reunion);

        if($nbseance_demiJ['demiJ'] == true && $checkM == 1 && $checkAM == 1){
//            return   $this->returnPage($seance->getIdComite()->getIdComite(), 'msg3error');
            $error[] =' Il vous reste qu\'une demi journée  !';
        }
//        $dateSeance = $seance->getDtSeance()->format('Ymd');

        $verifDate = $this->betweenDates($dateSeance, $seance->getIdReunion()->getDtDebPeriode(), $seance->getIdReunion()->getDtFinPeriode()); // retourn false s'il est hors du créneau

        foreach ($seanceReunion as $verifSeance) { // verifier les doublons
            if (($verifSeance->getDtSeance() == $dateSeance && $verifSeance->getMatin() ==  $checkM)
                ||
                ($verifSeance->getDtSeance() == $dateSeance && $verifSeance->getApresMidi() == $checkAM))
            {
                $error[] =' Impossible d\'ajouter une réunion  (Doublon)  !';
//                return $this->returnPage($seance->getIdComite()->getIdComite(), 'msg2error');
            }

        };
        if (false === $verifDate) {
            $error[] ='La date est hors période !';
//            return $this->returnPage($seance->getIdComite()->getIdComite(), 'msg1error');
        };

        if(empty($error)){
            $seance->setDtSeance($dateSeance);
            $seance->setMatin($checkM);
            $seance->setApresMidi($checkAM);
            $this->getEm()->persist($seance);
            $this->getEm()->flush();
            $succes = 'La séance à bien été modifiée';
        }

            $response = new Response(json_encode([
                    'succes' => $succes,
                    'errors' => $error
                ])
            );
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

//    }

    private function returnPage($idcomite, $message){
        return $this->redirect($this->generateUrl('list_comite',['cmte-seance' => $idcomite, 'msg' => $message]));
    }

    /**
     * @param TgSeance $seance
     * @return Response
     * @Route("/delete/ajax/{idSeance}", name="delete_ajax_seance")
     */
    public function deleteAjax(Request $request, TgSeance $idSeance){

       $redirectAftreDelet =  $request->headers->get('referer');
        return $this->render('tg_seance/modalSeanceComite/_form_delete.html.twig',['seance' => $idSeance,'redir' => $redirectAftreDelet]);
    }
    /**
     * supprimer une séance d'une réunion
     * @Route("delete/{idSeance}", name="supprimer_seance")
     */
    public function supprimerSeance(Request $request, TgSeance $seance): Response
    {
        if($request->isXmlHttpRequest()){
            $comite = $seance->getIdComite()->getIdComite();
            if ($this->isCsrfTokenValid('delete'.$seance->getIdSeance(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($seance);
                $entityManager->flush();
            }
            $response = new Response(json_encode([
                    'succes' => true,
//                    'errors' => $error
                ])
            );
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * supprimer toutes les séances d'une réunion
     * @Route("/supprimeSeances/{idReunion}/{idComite}", name="supprimer_seances_reunion")
     */
    public function supprimerTouteSeances(Request $request, TgReunion $reunion, TgComite $comite): Response
    {

        if ($this->isCsrfTokenValid('delete' . $reunion->getIdReunion(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $seances = $em->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite]);

            foreach ($seances as $seance) {
                $em->remove($seance);
                $em->flush();

            };
            $this->addFlash('success', 'Les réunion ont bien été supprimées');
        } else {
            $this->addFlash('error', 'Impossible de supprimer les réunion !');
        }
        return $this->redirect($this->generateUrl('list_comite', ['cmte-seance' => $comite->getIdComite()]));
    }

    /**
     * @param TgReunion $reunion
     * @param TgComite $comite
     * @return Response
     * Retourne une liste des seances dans une twig
     */
    public function listSeanceReunionParComite(TgReunion $reunion, TgComite $comite){

        $seances =  $this->getEm()->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite], ['dtSeance' =>'ASC']);

        return $this->render('tg_seance/listeseancereunioncmte.html.twig',['seances' => $seances]);
    }
    /**
     * @param TgReunion $reunion
     * @param TgComite $comite
     * @return Response
     * Retourne une liste des seances dans une twig
     */
    public function listSeanceTooltip(TgReunion $reunion, TgComite $comite){

        $seances =  $this->getEm()->getRepository(TgSeance::class)->findBy(['idReunion' => $reunion, 'idComite' => $comite], ['dtSeance' =>'ASC']);

        return $this->render('tg_seance/seancetooltip.html.twig',['seances' => $seances]);
    }

    /**
     * retourn true si il est dans les créneaux sinon false
     * @param $Date
     * @param $startDate
     * @param $endDate
     * @return bool
     */
    public function betweenDates($Date, $startDate, $endDate)
    {
        return ($Date >= $startDate) && ($Date <= $endDate);
    }

}
