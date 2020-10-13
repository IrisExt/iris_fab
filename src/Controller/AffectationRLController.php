<?php

namespace App\Controller;

use App\Entity\TgAffectation;
use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Entity\TlAvisPossibles;
use App\Entity\TlAvisProjet;
use App\Entity\TrProfil;
use App\Entity\TrStAffect;
use App\Entity\TrTypeEval;
use App\Repository\ParticipationRepository;
use App\Service\AffectationRLService;
use Doctrine\DBAL\DBALException;
use FOS\UserBundle\Model\UserManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AffectationRLController.
 *
 * @Route("affectation")
 */
class AffectationRLController extends BaseController
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var AffectationRLService
     */
    private $affectationService;
    /**
     * @var ParticipationRepository
     */
    private $participationRepository;

    public function __construct(Swift_Mailer $mailer, UserManagerInterface $userManager, AffectationRLService $affectationService, ParticipationRepository $participationRepository)
    {
        $this->mailer = $mailer;
        $this->userManager = $userManager;
        $this->affectationService = $affectationService;
        $this->participationRepository = $participationRepository;
    }

    /**
     * @Route("/quest_mmbre/{idComite}", name="quest_mmbre")
     * une validation de la publication par le CPS
     */
    public function paramQuestMbr(Request $request, TgComite $comite): Response
    {
        if ('POST' === $request->getMethod()) {
            $comite->setQuestPublie(true);
            $this->getEm()->persist($comite);
            $this->getEm()->flush();
//           $this->sendMailMmbre($comite);
            $this->addFlash('success', 'Le questionaire est publié .');
        }
        $avisComite = $this->emRep(TlAvisPossibles::class)->findBy(['idComite' => $comite], ['cdAvis' => 'ASC']) ?: null;

        return $this->render('affectation_rl/param_ques_mmbre.html.twig', [
            'avisComites' => $avisComite,
            'comite' => $comite,
        ]);
    }

    /**
     * @Route("/avis_mmbre/{idComite}", name="avis_member")
     * Reponse des memebres du comite au questionnaire
     *
     * @return Response
     */
    public function avisMmbreMember(TgComite $tgComite)
    {
        $tgProjets = $this->getDoctrine()->getRepository(TgProjet::class)->findBy(['idComite' => $tgComite->getIdComite()], ['lbAcro' => 'asc']) ?: null;

        $tlAvisPossibles = $this->getDoctrine()->getRepository(TlAvisPossibles::class)->findBy(['idComite' => $tgComite->getIdComite()]) ?: null;

        $idPersonne = $this->getUser()->getIdPersonne();

        $tlAvisProjet = $this->getDoctrine()->getRepository(TlAvisProjet::class)->findBy(['idPersonne' => $idPersonne]) ?: null;

        $tgParticipation = $this->emRep(TgParticipation::class)->findOneBy(['idPersonne' => $idPersonne, 'idComite' => $tgComite->getIdComite()]) ?: null;

        return $this->render('affectation_rl/param_avis_mmbre.html.twig', [
            'tgComite' => $tgComite,
            'tgProjets' => $tgProjets,
            'avis' => $tlAvisPossibles,
            'tlAvisProjets' => $tlAvisProjet,
            'soum_particip' => $tgParticipation->getQuestSoum(),
        ]);
    }

    /**
     * @Route("/addavismmbre/{idComite}", name="add_avis_mmbre")
     *
     * @return Response
     */
    public function avisMmbreAdd(TgComite $idComite, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $soumettre = $request->request->get('typSubmit');

            $avisProjets = $request->request->get('projets');

            $idPersonne = $this->getUser()->getIdPersonne();
            $tgPersonne = $this->getDoctrine()->getRepository(TgPersonne::class)->find($idPersonne) ?: null;

            $tgParticipant = $this->getDoctrine()->getRepository(TgParticipation::class)->findOneBy(['idPersonne' => $idPersonne, 'idComite' => $idComite->getIdComite()]) ?: null;
            $this->affectationService->avisMmbreAdd($tgPersonne, $avisProjets, $tgParticipant, $idComite, $soumettre);

            $response = new Response(json_encode([
                'result' => 1,
                'message' => 'ok',
                'data' => '',
            ]));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @return Response
     * @Route("/affec/{idComite}", name="affec_af")
     */
    public function affectationRL(TgComite $idComite)
    {
        $idProfil = $this->getEmProfil(9);
        $affectMembre = $this->participationRepository->affectRlParticipation($idComite, $idProfil);

        $projets = $this->emRep(TgProjet::class)->findBy(['idComite' => $idComite]);

        if (empty($affectMembre) || empty($projets)) {
            return $this->render('affectation_rl/affectation_non_exist.html.twig');
        }
        foreach ($affectMembre as $idMmbr) {
            $tabmbre[$idMmbr->getIdPersonne()->getIdPersonne()] = $idMmbr->getIdPersonne()->getIdPersonne();
        }
        $tabMbres = json_encode(array_keys($tabmbre));

        foreach ($projets as $idproj) {
            $tabproj[$idproj->getIdProjet()] = $idproj->getIdProjet();
        }
        $tabProjs = json_encode(array_keys($tabproj));

        // sur la phase 1 , pas de symbole O ,"Refus"
        $phase = $idComite->getIdAppel()->getNiveauEnCours()->getIdPhase()->getIdPhaseRef()->getIdPhaseRef();
        $notAffectPhase = ['O', 'E'];
        $notAfect = ['E'];
        $arrStatuts = 1 == $phase ?
            $arrStatuts = $this->emRep(TrStAffect::class)->findAffectWithPhase($notAffectPhase) :
                $this->emRep(TrStAffect::class)->findAffectRL($notAfect);

        $arrStatutsMembre = [];
        $arrStatutsMembre[] = 'EMP';
        $arrStatutsClassMembre = [];
        $arrStatutsClassMembre[] = 'emp';
        $arrStatutsClassColor = [];
        $arrStatutsClassColor[] = '#fff'; // couleur case vide
        foreach ($arrStatuts as $vStatut) {
            $arrStatutsMembre[] = strtoupper($vStatut->getSymbole());
            $arrStatutsClassMembre[] = strtolower($vStatut->getSymbole());
            $arrStatutsClassColor[] = empty($vStatut->getLbCouleur()) ? '#fff' : $vStatut->getLbCouleur();
        }
        $arrStatutsComputMembre = ['', 'rap', 'lect'];
        $arrNoCumul = [0, 3, 4];

        foreach ($arrStatutsMembre as $kc => $vc) {
            if (in_array($kc, $arrNoCumul)) {
                continue;
            }
            $loopStatus[$vc] = $arrStatutsComputMembre[$kc];
        }
        $tgAffectations = $this->emRep(TgParticipation::class)->affectationAfterAvis($idComite); // tgAffectation
        $tgAffectation = ($tgAffectations) ?: '';
        if (!empty($tgAffectation)) {
            foreach ($tgAffectation as $val) {
                $arrAffectations[$val->getIdProjet()->getIdProjet()][$val->getIdPersonne()->getIdPersonne()] = $val->getIdStAffect()->getSymbole();
            }
        } else {
            $arrAffectations = [];
        }
        $tlavisProj = $this->emRep(TgParticipation::class)->affectationData($idComite);
        $tlAvisProjet = $tlavisProj ?: [];
        $arrQuestionnaires = [];
        foreach ($tlAvisProjet as $item) {
            $arrQuestionnaires[$item->getIdProjet()->getIdProjet()][$item->getIdPersonne()->getIdPersonne()] =
                ['codeAvis' => $item->getCdAvis()->getCodeAvis()];
        }
        if ($this->isGranted('ROLE_CPS')) {
            $idComite->setNbQuestSoum(0);
            $this->getEm()->persist($idComite);
            $this->getEm()->flush();
        }

        return $this->render('affectation_rl/affectations_mmbre.html.twig', [
            'comite' => $idComite,
            'nbPrjs' => count($projets),
            'nbMembers' => count($affectMembre),
            'affectMembre' => $affectMembre,
            'affactProjet' => $projets,
            'tabMbres' => $tabMbres,
            'tabProjs' => $tabProjs,
            'arrAffectations' => $arrAffectations,
            'arrStatutsComputMembre' => $arrStatutsComputMembre,
            'arrNoCumul' => $arrNoCumul,
            'arrStatutsMembre' => $arrStatutsMembre,
            'arrStatutsClassMembre' => $arrStatutsClassMembre,
            'arrStatutsClassColor' => $arrStatutsClassColor,
            'arrQuestionnaires' => $arrQuestionnaires,
            'intSCMCount' => count($arrStatutsClassMembre),
        ]);
    }

    /**
     * @param null $openAccess
     *
     * @return Response
     * @Route("/submitRl/{idComite}/{openAccess}", name="submit_rl")
     */
    public function submitAffectationRl(Request $request, TgComite $idComite, $openAccess = null): ?Response
    {
        if ($request->isXmlHttpRequest() && false === $idComite->isBlDroitProjetOuvert()) {
            $arrStatuts = $this->emRep(TrStAffect::class)->findAll();
            $arrStatutsForPost = [];
            foreach ($arrStatuts as $ks => $vs) {
                $strSymboleStatut = trim(strtolower($vs->getSymbole()));
                $arrStatutsForPost[$strSymboleStatut] = $ks;
            }
            $arrPostAffectations = json_decode($request->request->get('form_affectations'), true);
            $arrPositions = json_decode($request->request->get('form_positions'), true);
            $arrIdMembres = json_decode($request->request->get('id_membres'), true);
            $arrIdProjets = json_decode($request->request->get('id_prjs'), true);
            $reload = $openAccess ? false : true; // si ouvrir accès

            $this->affectationAction($request, $idComite, $openAccess, $arrPostAffectations, $arrPositions, $arrIdMembres, $arrIdProjets);
            $success = '<div class="alert alert-success">L\'affectation des rapporteurs et lecteurs a bien été enregistrée</div>';
            $msg = json_encode(['success' => $success]);
            $response = new Response($msg);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @param $comite
     *
     * @return int
     *             envoi mail pour les memebres du comité "questionnaire publié"
     */
    public function sendMailMmbre($comite)
    {
        $mmbres = $this->getEmPartic()->findBy(['idComite' => $comite]);
        foreach ($mmbres as $mmbre) {
            $user = $this->userManager->findUserBy(['idPersonne' => $mmbre->getIdPersonne()]);
            if (null != $user) {
                $mbr_email[$user->getEmail()] = $user->getIdPersonne()->getLbNomUsage().' '.$user->getIdPersonne()->getLbPrenom();
            }
        }
        $message = (new Swift_Message('Notification publication questionnaire'))
            ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
            ->setTo($mbr_email)
            ->setBody(
                $this->renderView(
                    'emails/publication_questionnaire_mmbre.html.twig', ['comite' => $comite->getLbAcr()]),
                'text/html');

        // Send the message
        return $this->mailer->send($message);
    }

    /**
     * @param $request
     * @param $idComite
     * @param $openAccess
     * @param $arrPostAffectations
     * @param $arrPositions
     * @param $arrIdMembres
     * @param $arrIdProjets
     */
    private function affectationAction($request, $idComite, $openAccess, $arrPostAffectations, $arrPositions, $arrIdMembres, $arrIdProjets)
    {
        $st_affect = ['R', 'L', 'O', 'X'];

        $deleteAffecComite = $this->emRep(TgParticipation::class)->projWhithCmteAffec($idComite, $st_affect);

        $em = $this->getEm();
        foreach ($deleteAffecComite as $tgAffect) {
            $em->remove($tgAffect);
            $em->flush();
        }
        $emHabProj = $this->getEm();
        foreach ($arrIdProjets as $key => $proj) {
            $projet = $this->emRep(TgProjet::class)->find($proj);
            $existeHab = $this->emRep(TgHabilitation::class)->habiMbreByProjet($projet);
            if ($existeHab) {
                foreach ($existeHab as $habi) {
                    $habi->removeIdProjet($projet);
                    $habi->removeIdComite($idComite);
                    $emHabProj->flush();
                }
            }
        }

        foreach ($arrPositions as $key => $value) {
            $intPosMembre = $value % $request->request->get('nb_membres');
            $intPosProjet = floor($value / $request->request->get('nb_membres'));

            $tgPersonne = $this->emRep(TgPersonne::class)->find($arrIdMembres[$intPosMembre]);
            $tgProjet = $this->emRep(TgProjet::class)->find($arrIdProjets[$intPosProjet]);
            $trStAffect = $this->emRep(TrStAffect::class)->findOneBy(['symbole' => strtoupper($arrPostAffectations[$key])]);
            try {
                $hab_prof = null;
                if ('R' === $trStAffect->getSymbole()) {
                    $hab_prof = $this->emRep(TrProfil::class)->find(18);
                } elseif ('L' === $trStAffect->getSymbole()) {
                    $hab_prof = $this->emRep(TrProfil::class)->find(17);
                }

                if (null !== $openAccess && null !== $hab_prof) { // ouvrir les accès pour les R/L
                    $tghabil = $this->emRep(TgHabilitation::class)->findOneBy(['idPersonne' => $tgPersonne, 'idProfil' => $hab_prof]);
                    $tghabilitation = $tghabil ?: new TgHabilitation();
                    $tghabilitation
                        ->setIdPersonne($tgPersonne)
                        ->setIdProfil($hab_prof)
                        ->setLbRespMaj($this->getUserConnect()->getLbNomUsage().' '.$this->getUserConnect()->getIdPersonne())
                        ->addIdProjet($tgProjet)
                        ->addIdComite($idComite);
                    $em->persist($tghabilitation);

                    $idComite->setBlDroitProjetOuvert(true);
                    $em->persist($idComite);
                }
                $typeEval = $this->emRep(TrTypeEval::class)->find(1); // type expertise
                $em = $this->getEm();
                $tgAfect = new TgAffectation();
                $tgAfect->setIdPersonne($tgPersonne)
                    ->setIdProfil($hab_prof)
                    ->setIdProjet($tgProjet)
                    ->setIdStAffect($trStAffect)
                    ->setIdType($typeEval); // type expertise
                $em->persist($tgAfect);
            } catch (DBALException $e) {
                $e->getMessage();
            }
        }
        $emHabProj->flush();
        $em->flush();
    }

    /**
     * @param $arobase
     * @param $inthat
     *
     * @return false|string
     */
    private function after($arobase, $inthat)
    {
        if (!is_bool(strpos($inthat, $arobase))) {
            return substr($inthat, strpos($inthat, $arobase) + strlen($arobase));
        }
    }

    /**
     * @Route("/export_rl/{tgComite}", name="export_affec_RL")
     *
     * @return Response
     *
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcelAffectationRl(TgComite $tgComite)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet
         */
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $affectMembre = $this->emRep(TgParticipation::class)->findBy(['idComite' => $tgComite, 'idProfil' => 9]);

        $projets = $this->emRep(TgProjet::class)->findBy(['idComite' => $tgComite]);

        foreach ($affectMembre as $idMmbr) {
            $sheet->setCellValue('A1', 'Hello World !');
            $tabmbre[] = $idMmbr->getIdPersonne()->getIdPersonne();
        }

        foreach ($projets as $idproj) {
            $tabproj[] = $idproj->getIdProjet();
        }

        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $publicDirectory = $this->getParameter('kernel.project_dir').'/public/affectationRL';
        $excelFilepath = $publicDirectory.'affectation_RL_'.$tgComite.'.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        // Create a Temporary file in the system
        $fileName = 'affectation_RL_'.$tgComite.'.xlsx';

        return $this->file($excelFilepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
