<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\TrRnsr;
use App\Entity\TgAdrMail;
use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgCoordinationProj;
use App\Entity\TgCoutPrev;
use App\Entity\TgDocument;
use App\Entity\TgFormulaire;
use App\Entity\TgHabilitation;
use App\Entity\TgIdExternes;
use App\Entity\TgMcLibre;
use App\Entity\TgNonSouhaite;
use App\Entity\TgOrganisme;
use App\Entity\TgParametre;
use App\Entity\TgPartenariat;
use App\Entity\TgPersonne;
use App\Entity\TgPhase;
use App\Entity\TgProjet;
use App\Entity\TgResume;
use App\Entity\TrCivilite;
use App\Entity\TrInfo;
use App\Entity\TrInfRech;
use App\Entity\TrLangue;
use App\Entity\TrPays;
use App\Entity\TrProfil;
use App\Entity\TrSiret;
use App\Entity\TrTypePart;
use App\Entity\TgBloc;
use App\Entity\TrTypIdExt;
use App\Entity\User;
use App\Form\Blocs\Type\BlPartenaireEtrType;
use App\Form\Blocs\Type\BlPartenairePufType;
use App\Form\Blocs\Type\BlPartenairePrfType;
use App\Form\Blocs\Type\BlParticipantsType;
use App\Repository\TgNonSouhaiteRepository;
use App\Repository\TgBlocRepository;
use App\Repository\TgProjetRepository;
use App\Service\FormulaireService;
use App\Service\GeoDBService;
use App\Service\ReferentielService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TgFormulaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use \Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\TgComiteRepository;
use \Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class FormulaireController
 * @package App\Controller
 * @Route("/soumission")
 */
class FormulaireController extends AbstractController
{

    /**
     * @var FormulaireService
     */
    private $formulaireService;

    /**
     * @var TgFormulaireRepository
     */
    private $tgFormulaireRepository;

    /**
     * @var TgProjetRepository
     */
    private $tgProjetRepository;

    /**
     * @var TgComiteRepository
     */
    private $tgComiteRepository;

    /**
     * @var TgBlocRepository
     */
    private $tgBlocRepository;

    /**
     * @var TgNonSouhaiteRepository
     */
    private $nonSouhaiteRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var GeoDBService
     */
    private $geoDBService;

    /**
     * @var ReferentielService
     */
    private $referentielService;

    /**
     * FormulaireController constructor.
     * @param FormulaireService $formulaireService
     * @param TgFormulaireRepository $tgFormulaireRepository
     * @param TgProjetRepository $tgProjetRepository
     * @param TgComiteRepository $tgComiteRepository
     * @param TgBlocRepository $tgBlocRepository
     * @param TgNonSouhaiteRepository $nonSouhaiteRepository
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param GeoDBService $geoDBService
     * @param ReferentielService $referentielService
     */
    public function __construct(
        FormulaireService $formulaireService,
        TgFormulaireRepository $tgFormulaireRepository,
        TgProjetRepository $tgProjetRepository,
        TgComiteRepository $tgComiteRepository,
        TgBlocRepository $tgBlocRepository,
        TgNonSouhaiteRepository $nonSouhaiteRepository,
        TranslatorInterface $translator,
        ValidatorInterface $validator,
        SessionInterface $session,
        GeoDBService $geoDBService,
        ReferentielService $referentielService
    ){
        $this->formulaireService = $formulaireService;
        $this->tgFormulaireRepository = $tgFormulaireRepository;
        $this->tgProjetRepository = $tgProjetRepository;
        $this->tgComiteRepository = $tgComiteRepository;
        $this->tgBlocRepository = $tgBlocRepository;
        $this->nonSouhaiteRepository = $nonSouhaiteRepository;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->session = $session;
        $this->geoDBService = $geoDBService;
        $this->referentielService = $referentielService;
    }

    /**
     * @Route("/appel/{tgAppelProj}/formulaire/{tgFormulaire}/bloc/{tgBloc}/projet/{idProjet}", name="bloc")
     * @param TgAppelProj $tgAppelProj
     * @param TgFormulaire $tgFormulaire
     * @param TgBloc $tgBloc
     * @param null $idProjet
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function Bloc(TgAppelProj $tgAppelProj, TgFormulaire $tgFormulaire, TgBloc $tgBloc, $idProjet=null, Request $request)
    {

        $idAppel = $tgAppelProj->getIdAppel();
        $idFormulaire = $tgFormulaire->getIdFormulaire();
        $idBloc = $tgBloc->getIdBloc();

        $listBlocs = $this->formulaireService->GetListBlocs($idFormulaire);
        $nextBlock = ($listBlocs)? $this->formulaireService->getNextBlockByRang($listBlocs, $idBloc) : 1;
        $prevBlock = ($listBlocs)? $this->formulaireService->getPreviousBlockByRang($listBlocs, $idBloc) : 1;

        $access_porteur = $this->container->get('security.authorization_checker')->isGranted('ROLE_PORTEUR_PROJET');
        $access_respsc = $this->container->get('security.authorization_checker')->isGranted('ROLE_RE_SCI');
        $access_respad = $this->container->get('security.authorization_checker')->isGranted('ROLE_RE_ADM');

        // $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idAppel, 'porteur' => $idUser]) ?: new TgProjet();
        $tgProjet = ($idProjet)? $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]) : new TgProjet();


        $idUser = $this->getUser()->getIdPersonne();

        $tgProjet = ($idProjet)? $this->tgProjetRepository->findOneby(['idProjet' => $idProjet, 'porteur' => $idUser]) : new TgProjet();

        $tgHabilitation = $this->getDoctrine()->getRepository(TgHabilitation::class)->findOneby(['idPersonne' => $idUser])?: '';
        if($tgHabilitation && !$tgProjet) {
            $habProjets = array();
            foreach($tgHabilitation->getIdProjet() as $habProj) {
                $habProjets[] =  $habProj->getIdProjet();
            }

            if(!in_array($idProjet, $habProjets)) throw new AccessDeniedException('User tried to access a page without having permissions');
        }

        if ($idProjet && !$tgProjet) throw new AccessDeniedException('User tried to access a page without having permissions');

        $tgPhase =  ($tgAppelProj->getNiveauEnCours())? $this->getDoctrine()->getRepository(TgPhase::class)->findOneby(['idPhase' =>$tgAppelProj->getNiveauEnCours()->getIdPhase()]) : 1;
        $idPhase = ($tgPhase)? $tgPhase->getIdPhaseRef()->getIdPhaseRef():1;
        ///////// vérifier si on est dans le niveau soumission /////////////////
        $niveau = ($tgAppelProj->getNiveauEnCours())? $tgAppelProj->getNiveauEnCours()->getIdTypeNiveu()->getIdTypeNiveu() : 1;
        if(1 !== $niveau) throw new AccessDeniedException('User tried to access a page without having permissions');
        ///////// vérifier la phase /////////////////
        if(1 === $idPhase) {
            // vérifié si c'est un resp. sc pour un partenaire
            if ($access_porteur) {
                $tgProjet = ($idProjet)? $this->tgProjetRepository->findOneby(['idProjet' => $idProjet,'porteur' => $idUser]) : new TgProjet();
            } elseif ($access_respsc || $access_respad) {
                $tgPartenaire =  ($request->query->get('p')) ?
                    $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $request->query->get('p')]) : null;
                $tgProjet = ($tgPartenaire)? $this->tgProjetRepository->findOneby(['idProjet' => $tgPartenaire->getIdProjet(), 'porteur' => $idUser]) : new TgProjet();

                $partenaireLink = array();
                foreach ($listBlocs as $key => $bloc) {
                    if ("BlPartenariatType" === $bloc['form_type']) {
                        $partenaireLink[$bloc['ordre']] = $bloc;
                    }
                }
                $listBlocs = $partenaireLink;
            }
        } else {
            throw new AccessDeniedException('User tried to access a page without having permissions');
        }


        $tgComite = $this->tgComiteRepository->findOneby(['idAppel' => $idAppel])?: new TgComite();
        $idComite =  $tgComite->getIdComite();

        $idInfraFi = '';
        if ($tgProjet) $idInfraFi = ($tgProjet->getIdInfraFi()) ? $tgProjet->getIdInfraFi()->getIdInstFi() : '';

        $form_before_permissions = $this->formulaireService->GetFormType($tgBloc, $tgProjet, $options=array(), $listBlocs);
        ///// get idPhase encours /////
        $options = array('hidden' => array(), 'op_disabled' => array());
        foreach ($form_before_permissions->all() as $key=>$fields) {
            $options['hidden'][$key] = $this->formulaireService->manageFormPhaseVisibility($idAppel, $idPhase, null, $key, $options);
            $options['op_disabled'][$key] = $this->formulaireService->manageFormPhasePermissions($idAppel, $idPhase, null, $key, $options);
        }

        $form = $this->formulaireService->GetFormType($tgBloc, $tgProjet, $options, $listBlocs);
        $form->handleRequest($request);

        $errorsString = '';$errors=array();
        $blocValidation=array();
        if ($form->isSubmitted()) {
            /////////////////////////////  One block or All block  /////////////////////////////////////////
            /////////////////////////////////////////////////////////////
            $this->session->start();
            $this->session->remove("globalValidationActivated");
            $this->session->remove("SoumissionValide");
            $this->session->remove("blocValidation");
            $idUser = $this->getUser()->getIdPersonne();
            // save in db when click "suivant" button, depending on block, and redirect to next bloc in rank
            if ($form->getClickedButton() === $form->get('saveBloc') && 'saveBloc' === $form->getClickedButton()->getName()) {
                // which block ??
                $processingBloc = 'processing'.$tgBloc->getClassName();
                $process = $this->formulaireService->$processingBloc($tgProjet, $idAppel, $form, $idInfraFi, $idUser, $tgPhase);
                if($process) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $nextBlock, 'idProjet' => $tgProjet->getIdProjet()]);
            }
            if ($form->getClickedButton() === $form->get('saveBlocWithoutRedirect') && 'saveBlocWithoutRedirect' === $form->getClickedButton()->getName()) {
                $processingBloc = 'processing'.$tgBloc->getClassName();
                $process = $this->formulaireService->$processingBloc($tgProjet, $idAppel, $form, $idInfraFi, $idUser, $tgPhase);
                if($process) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $idBloc, 'idProjet' => $tgProjet->getIdProjet()]);
            }

            // save in db when click "Soumettre le formulaire" button, if no errors after validation, redirect to home page
            if ($form->getClickedButton() === $form->get('saveFormulaire') && 'saveFormulaire' === $form->getClickedButton()->getName()) {
                $errorsPoleComp=array(); $errorsInfra=array(); $errorsCo=array();

                /// search errors by bloc
                $formulaireNonValide = 1; $errorsSoum = array(); $errorsResume= array(); $errorsDoc = array();
                foreach ($listBlocs as $Bloc){
                    $blocValidation[$Bloc['id_bloc']] = 1;
                    $group = "bloc_".$Bloc['form_type'];
                    $errorsByBloc =  $this->validator->validate($tgProjet,null, $group);

                    if(count($errorsByBloc) > 0) {
                        $formulaireNonValide = 0;
                        $blocValidation[$Bloc['id_bloc']] = 0;
                    }
                    if('BlSoumettreType' === $Bloc['form_type']) {
                        $blocValidation[$Bloc['id_bloc']] = $formulaireNonValide;
                    }

                    if('BlResumeType' === $Bloc['form_type']) {
                        $errorsResumeFr = array(); $errorsResumeEn = array();
                        $tgResumeFr = $this->getDoctrine()->getRepository(TgResume::class)->findOneby(['idProjet' => $tgProjet->getIdProjet(), 'idLangue' => 1]);
                        if(!$tgResumeFr || ($tgResumeFr && !$tgResumeFr->getLbTexte())) {
                            $errorsResumeFr[] = [
                                'field' => 'BlResumeType_resumeFr',
                                'message'      => 'Le résumé en français doit être renseigné ',
                            ];
                        }
                        $tgResumeEn = $this->getDoctrine()->getRepository(TgResume::class)->findOneby(['idProjet' => $tgProjet->getIdProjet(), 'idLangue' => 2]);
                        if(!$tgResumeEn || ($tgResumeEn && !$tgResumeEn->getLbTexte())) {
                            $errorsResumeEn[] = [
                                'field' => 'BlResumeType_resumeEn',
                                'message'      => 'Le résumé en anglais doit être renseigné ',
                            ];
                        }

                        if(count($errorsResumeEn) >0 || count($errorsResumeFr) >0) {
                            $blocValidation[$Bloc['id_bloc']] = 0;
                        }
                    }

                    if('BlInfoComplType' === $Bloc['form_type']) {
                        $data = $form->getData();
                        if(true === $data->getBlDemLabel() && 0 === $data->getIdPoleComp()->count()) {
                            $errorsPoleComp =  $this->validator->validate($tgProjet,null, 'BlInfoComplType_pc');
                            $errorsPoleComp = $this->constraintViolationValidation($errorsPoleComp);
                        }
                        if(true === $data->getBlInfraRecherche() && 0 === $data->getIdInfRech()->count()) {
                            $errorsInfra =  $this->validator->validate($tgProjet,null, 'BlInfoComplType_ir');
                            $errorsInfra = $this->constraintViolationValidation($errorsInfra);
                        }
                        if(true === $data->getBlDemCofi() && 0 === $data->getIdCoFi()->count()) {
                            $errorsCo =  $this->validator->validate($tgProjet,null, 'BlInfoComplType_co');
                            $errorsCo = $this->constraintViolationValidation($errorsCo);
                        }
                        if(count($errorsPoleComp) >0 || count($errorsInfra) >0 || count($errorsCo) >0) {
                            $blocValidation[$Bloc['id_bloc']] = 0;
                        }
                    }

                    if('BlDocScientifiqueType' === $Bloc['form_type']) {
                        $errorsD = $this->formulaireService->validDoc($tgProjet);

                        if(count($errorsD)> 0) {
                            $errorsDoc[$Bloc['id_bloc']] = $errorsD;
                            $blocValidation[$Bloc['id_bloc']] = 0;
                        }
                    }

                    if('BlPartenariatType' === $Bloc['form_type']) {
                        $errorsPartenaire= array(); $errorsEtr= array();
                        $errorsPrf=array();
                        $errorsPuf=array();
                        $partenaires = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idProjet' => $tgProjet->getIdProjet()]);
                        if(!$partenaires) {
                            $errorsPartenaire[] = [
                                'field' => 'BlPartenariatType',
                                'message'      => 'Au moins un partenaire doit être renseigné ',
                            ];
                        }
                        // validation des partenaires etrangers
                        $partenairesEtr = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'ETR']);
                        if($partenairesEtr) {
                            foreach ($partenairesEtr as $partenaireEtr) {
                                $field = $partenaireEtr->getIdPartenaire();
                                $labo = ($partenaireEtr->getLaboratoire())? $partenaireEtr->getLaboratoire()->getLbNomFr() : null;
                                $city = ($partenaireEtr->getHebergeur())? $partenaireEtr->getHebergeur()->getCdRnsr()->getIdAdresse()->getVille() : null;
                                if (!$labo) {
                                    $message = 'le champ "Laboratoire" pour le partenaire '.$partenaireEtr->getHebergeur().' doit être renseigné ';
                                    $errorsEtr[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$city) {
                                    $message = 'le champ "Ville" pour le partenaire '.$partenaireEtr->getHebergeur().' doit être renseigné ';
                                    $errorsEtr[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }

                                if(count($partenaireEtr->getIdPersonne())== 0) {
                                    $message = 'Au moin un participant doit être renseigné pour le partenaire '.$partenaireEtr->getHebergeur();
                                    $errorsEtr[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                            }
                        }


                        // validation des partenaires privés
                        $partenairesPrf = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'PRF']);
                        if($partenairesPrf) {
                            foreach ($partenairesPrf as $partenairePrf) {
                                $field = $partenairePrf->getIdPartenaire();
                                $city = ($partenairePrf->getTutGest())? $partenairePrf->getTutGest()->getSiret()->getIdAdresse()->getVille() : null;
                                $country = ($partenairePrf->getTutGest())? $partenairePrf->getTutGest()->getSiret()->getIdAdresse()->getCdPays() : null;
                                $nomGestAdm = ($partenairePrf->getGestAdm())? $partenairePrf->getGestAdm()->getLbNomUsage() : null;
                                $prenomGestAdm = ($partenairePrf->getGestAdm())? $partenairePrf->getGestAdm()->getLbPrenom() : null;
                                $emailGestAdm = ($partenairePrf->getGestAdm())? $partenairePrf->getGestAdm()->getIdAdrMail() : null;

                                if (!$city) {
                                    $message = 'le champ "Ville de la tutelle gestionnaire" pour le partenaire '.$partenaireEtr->getTutGest().' doit être renseigné ';
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$country) {
                                    $message = 'le champ "Pays de la tutelle gestionnaire" pour le partenaire '.$partenaireEtr->getTutGest().' doit être renseigné ';
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$nomGestAdm) {
                                    $message = 'le champ "Nom du gestionnaire administratif" pour le partenaire '.$partenairePrf->getTutGest().' doit être renseigné ';
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$prenomGestAdm) {
                                    $message = 'le champ "Prénom du gestionnaire administratif" pour le partenaire '.$partenairePrf->getTutGest().' doit être renseigné ';
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (count($emailGestAdm) < 1) {
                                    $message = 'le champ "Courriel du gestionnaire administratif" pour le partenaire '.$partenairePrf->getTutGest().' doit être renseigné ';
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if(count($partenairePrf->getIdPersonne())== 0) {
                                    $message = 'Au moin un participant doit être renseigné pour le partenaire '.$partenairePrf->getHebergeur();
                                    $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }

                                if(2 === $idPhase) {
                                    $nomRespJ = ($partenairePrf->getRepJuridique())? $partenairePrf->getRepJuridique()->getLbNomUsage() : null;
                                    $prenomRespJ = ($partenairePrf->getRepJuridique())? $partenairePrf->getRepJuridique()->getLbPrenom() : null;
                                    $functionRespJ = ($partenairePrf->getRepJuridique())? $partenairePrf->getRepJuridique()->getFonction() : null;

                                    $banquePrf = ($partenairePrf->getTutGest())? $partenairePrf->getTutGest()->getBanque() : null;
                                    $ribPrf = ($partenairePrf->getTutGest())? $partenairePrf->getTutGest()->getRib() : null;
                                    $ibanPrf = ($partenairePrf->getTutGest())? $partenairePrf->getTutGest()->getIban() : null;

                                    if (!$nomRespJ) {
                                        $message = 'le champ "Nom du responsable juridique" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$prenomRespJ) {
                                        $message = 'le champ "Prénom du responsable juridique" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$functionRespJ) {
                                        $message = 'le champ "Fonction du responsable juridique" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }

                                    if (!$banquePrf) {
                                        $message = 'le champ "Nom de la banque de la tutelle gestionnaire" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$ribPrf) {
                                        $message = 'le champ "Rib de la tutelle gestionnaire" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$ibanPrf) {
                                        $message = 'le champ "Iban de la tutelle gestionnaire" pour le partenaire '.$partenairePrf->getHebergeur().' doit être renseigné ';
                                        $errorsPrf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                }
                            }
                        }

                        // validation des partenaires privés
                        $partenairesPuf = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'PUF']);
                        if($partenairesPuf) {
                            foreach ($partenairesPuf as $partenairePuf) {
                                $field = $partenairePuf->getIdPartenaire();
                                $city = ($partenairePuf->getHebergeur())? $partenairePuf->getHebergeur()->getCdRnsr()->getIdAdresse()->getVille() : null;
                                $country = ($partenairePuf->getHebergeur())? $partenairePuf->getHebergeur()->getCdRnsr()->getIdAdresse()->getCdPays() : null;
                                $nomGestAdm = ($partenairePuf->getGestAdm())? $partenairePuf->getGestAdm()->getLbNomUsage() : null;
                                $prenomGestAdm = ($partenairePuf->getGestAdm())? $partenairePuf->getGestAdm()->getLbPrenom() : null;
                                $emailGestAdm = ($partenairePuf->getGestAdm())? $partenairePuf->getGestAdm()->getIdAdrMail() : null;
                                $labo = ($partenairePuf->getHebergeur())? $partenairePuf->getHebergeur()->getLbLaboratoire() : null;
                                $nomDirLab = ($partenairePuf->getDirLabo())? $partenairePuf->getDirLabo()->getLbNomUsage() : null;
                                $prenomDirLab = ($partenairePuf->getDirLabo())? $partenairePuf->getDirLabo()->getLbPrenom() : null;
                                $emailDirLab = ($partenairePuf->getDirLabo())? $partenairePuf->getDirLabo()->getIdAdrMail() : null;

                                if (!$city) {
                                    $message =  'le champ "Ville de la tutelle hébergeante" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$country) {
                                    $message =  'le champ "Pays de la tutelle hébergeante" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$labo) {
                                    $message =  'le champ "Nom laboratoire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$nomDirLab) {
                                    $message =  'le champ "Nom du directeur du laboratoire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$prenomDirLab) {
                                    $message =  'le champ "Prénom du directeur du laboratoire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }

                                if ($emailDirLab && count($emailDirLab) < 1) {
                                    $message =  'le champ "Courriel du directeur du laboratoire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$nomGestAdm) {
                                    $message =  'le champ "Nom du gestionnaire administratif" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if (!$prenomGestAdm) {
                                    $message =  'le champ "Prénom du gestionnaire administratif" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if ($emailDirLab && count($emailGestAdm) < 1) {
                                    $message = 'le champ "Courriel du gestionnaire administratif" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                 if (!$partenairePuf->getLbDeleguation()) {
                                     $message =  'le champ "Délégation" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                     $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                 }
                                if(count($partenairePuf->getIdPersonne())== 0) {
                                    $message =  'Au moin un participant doit être renseigné pour le partenaire '.$partenairePuf->getHebergeur();
                                    $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                }
                                if(2 === $idPhase) {
                                    $nomRespJ = ($partenairePuf->getRepJuridique())? $partenairePuf->getRepJuridique()->getLbNomUsage() : null;
                                    $prenomRespJ = ($partenairePuf->getRepJuridique())? $partenairePuf->getRepJuridique()->getLbPrenom() : null;
                                    $functionRespJ = ($partenairePuf->getRepJuridique())? $partenairePuf->getRepJuridique()->getFonction() : null;

                                    $siretPuf = ($partenairePuf->getTutGest())? $partenairePuf->getTutGest()->getSiret() : null;
                                    $tutelleGestPuf = ($partenairePuf->getTutGest())? $partenairePuf->getTutGest()->getLbNomFr() : null;
                                    $banquePuf = ($partenairePuf->getTutGest())? $partenairePuf->getTutGest()->getBanque() : null;
                                    $ribPuf = ($partenairePuf->getTutGest())? $partenairePuf->getTutGest()->getRib() : null;
                                    $ibanPuf = ($partenairePuf->getTutGest())? $partenairePuf->getTutGest()->getIban() : null;
                                    $villePuf= null; $paysPuf= null;
                                    if ($partenairePuf->getTutGest()) {
                                        $villePuf = ($partenairePuf->getTutGest()->getSiret()->getIdAdresse())? $partenairePuf->getTutGest()->getSiret()->getIdAdresse()->getVille() : null;
                                        $paysPuf = ($partenairePuf->getTutGest()->getSiret()->getIdAdresse())? $partenairePuf->getTutGest()->getSiret()->getIdAdresse()->getCdPays() : null;
                                    }

                                    if (!$nomRespJ) {
                                        $message = 'le champ "Nom du responsable juridique" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$prenomRespJ) {
                                        $message = 'le champ "Prénom du responsable juridique" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$functionRespJ) {
                                        $message = 'le champ "Fonction du responsable juridique" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }

                                    if (!$siretPuf) {
                                        $message = 'le champ "Siret de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$tutelleGestPuf) {
                                        $message = 'le champ "Nom de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$banquePuf) {
                                        $message = 'le champ "Nom de la banque de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$ribPuf) {
                                        $message = 'le champ "Rib de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$ibanPuf) {
                                        $message = 'le champ "Iban de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }

                                    if (!$villePuf) {
                                        $message = 'le champ "La ville de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                    if (!$paysPuf) {
                                        $message = 'le champ "Le pays de la tutelle gestionnaire" pour le partenaire '.$partenairePuf->getHebergeur().' doit être renseigné ';
                                        $errorsPuf[] = $this->formulaireService->getErrorsValidation($field, $message);
                                    }
                                }
                            }
                        }

                        if(count($errorsPartenaire) > 0 || count($errorsEtr) > 0 || count($errorsPrf) > 0 || count($errorsPuf) > 0) {
                            $blocValidation[$Bloc['id_bloc']] = 0;
                        }
                    }

                    $errors = $this->formulaireService->validateSoumission($Bloc, $tgProjet, $form);
                    if(count($errors)> 0) {
                        $errorsSoum[$Bloc['id_bloc']] = $errors;
                        $blocValidation[$Bloc['id_bloc']] = 0;
                    }
                }

                $errorsForm =  $this->validator->validate($tgProjet,null, ["formulaire"]);
                $errorsForm = $this->constraintViolationValidation($errorsForm)?:array();
                $errors = array_merge(
                    $errorsResumeFr,
                    $errorsResumeEn,
                    $errorsPoleComp,
                    $errorsInfra,
                    $errorsCo,
                    $errorsDoc,
                    $errorsSoum,
                    $errorsForm,
                    $errorsPartenaire,
                    $errorsEtr,
                    $errorsPrf,
                    $errorsPuf
                );

                if (count($errors) > 0) {
                    $this->session->start();
                    $this->session->set("globalValidationActivated", $errors);
                    $this->session->set("blocValidation", $blocValidation);
                } else {
                    // TODO  if valid formulaire save in DB and redirect to home page, to be continued !
                    $this->session->start();
                    $this->session->set("SoumissionValide", 'success');
                    $mailsPorteur = ($tgProjet->getPorteur())? $tgProjet->getPorteur()->getAdrMailNotif() : '';
                    $mailPorteur = '';
                    if ($mailsPorteur) {
                        foreach ($mailsPorteur as $mail) {
                            $mailPorteur = $mail->getAdrMailNotif()? : '';
                        }
                        if ($mailPorteur) $this->formulaireService->sendMailSoumission($mailPorteur);
                    }
                    $this->redirectToRoute('accueil');
                }

            }
        }

        $idInfraFi = '';
        if ($tgProjet) $idInfraFi = ($tgProjet->getIdInfraFi()) ? $tgProjet->getIdInfraFi()->getIdInstFi(): '';

        $lbAppel = $tgAppelProj->getLbAppel()? : null;

        $listePartenaires =array();
        $projetName = '';
        if ($tgProjet) $projetName = ($tgProjet->getIdInfraFi()) ? $tgProjet->getIdInfraFi()->getLbNom() : '';

        $processingBloc = 'processing' . $tgBloc->getClassName();

        $previousBlock =  $this->generateUrl('bloc', array('tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $prevBlock, 'idProjet' => $tgProjet->getIdProjet()));

        return $this->$processingBloc(
            $form,
            $idBloc,
            $idComite,
            $idFormulaire,
            $request,
            $tgProjet,
            $tgComite,
            $tgFormulaire,
            $listBlocs,
            $nextBlock,
            $previousBlock,
            $idAppel,
            $projetName,
            $lbAppel,
            $idInfraFi,
            $listePartenaires,
            $this->session->get("blocValidation"),
            $errors
        );

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @param $errors
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlSoumettreType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation,
        $errors
    ){

        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $idProjet = $tgProjet->getIdProjet()?: null;
        return $this->render('bloc/BlSoumettreType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $errors,
            'validSoumission' => $this->session->get("SoumissionValide"),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlSyntheseType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $partenaires = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet()]) : '';
        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlSyntheseType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'tgProjet' => $tgProjet,
            'partenaires' => $partenaires,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return Response
     */
    private function processingBlInstFiType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');
        $tgBlock = $this->tgBlocRepository->findOneBy(['idBloc' => $idBloc]);

        $idInfrastructureFi = ($tgProjet->getIdInfraFi() !== null && 'BlInstFiType' === $tgBlock->getClassName()) ? $tgProjet->getIdInfraFi()->getIdInstFi(): '';
        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlInstFiType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'idInstrument' => $idInfrastructureFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlIdentProjType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;

        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $lbAcro = $this->getDoctrine()->getRepository(TgAppelProj::class)->findOneBy(['idAppel' => $idAppel]);
        $acro =  ($lbAcro)? mb_strtoupper($lbAcro->getLbAcronyme()) : '';

        $acros = array();
        $projets = $this->tgProjetRepository->getAcros($tgProjet->getIdProjet(), $idAppel)? : null;

        if($projets) {
            foreach ($projets as $projet) {
                $acros[] = $projet->getLbAcro();
            }
        }
        //dd($acros);
        $mntMax = ($tgProjet->getIdInfraFi()) ? $tgProjet->getIdInfraFi()->getMntMax() : '';
        $mntmin = ($tgProjet->getIdInfraFi()) ? $tgProjet->getIdInfraFi()->getMntMin() : '';

        $comites =  $this->getDoctrine()->getRepository(TgComite::class)->findBy(['idAppel' => $idAppel]);
        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlIdentProjType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'MntMax' =>  $mntMax,
            'MntMin' =>  $mntmin,
            'lbAcro' => $acro,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'comites' => $comites,
            'acros' => $acros,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlDocScientifiqueType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $paramsFile = $this->getDoctrine()->getRepository(TgParametre::class)->findBy(['idAppel' => $idAppel]);

        $FmtDocSc = ($this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['lbCode' => 'FMT_DOC_SC'])->getLbValeur())?:'';

        $tgAppelProj = $this->getDoctrine()->getRepository(TgAppelProj::class)->findOneBy(['idAppel' => $idAppel]);
        $tgPhase =  $this->getDoctrine()->getRepository(TgPhase::class)->findOneby(['idPhase' =>$tgAppelProj->getNiveauEnCours()->getIdPhase()]);

        $tgdoc = $this->getDoctrine()->getRepository(TgDocument::class)->findOneBy(['idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 1, 'idPhase' => $tgPhase->getIdPhase()]);

        $fileName = ($tgdoc)? $tgdoc->getLbNomFichier() : '';
        $trLangue = ($tgdoc)? $tgdoc->getIdLangue() : '';
        $lbLangue = ($trLangue)? $trLangue->getIdLangue() : '';

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlDocScientifiqueType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'fileName' => $fileName,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'FmtDocSc' => $FmtDocSc,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'paramsFile' => $paramsFile,
            'lbLangue' => $lbLangue,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlAnnexePrepropositionType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $paramsFile = $this->getDoctrine()->getRepository(TgParametre::class)->findBy(['idAppel' => $idAppel]);

        $tgAppelProj = $this->getDoctrine()->getRepository(TgAppelProj::class)->findOneBy(['idAppel' => $idAppel]);
        $tgPhase =  $this->getDoctrine()->getRepository(TgPhase::class)->findOneby(['idPhase' =>$tgAppelProj->getNiveauEnCours()->getIdPhase()]);

        $tgdoc = $this->getDoctrine()->getRepository(TgDocument::class)->findOneBy(['idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 2, 'idPhase' => $tgPhase->getIdPhase()]);
        $fileName = ($tgdoc)? $tgdoc->getLbNomFichier() : '';

        $FmtDocSc = ($this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['lbCode' => 'FMT_AN_PRE_PROPOSOTI'])->getLbValeur())?:'';

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlAnnexePrepropositionType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'paramsFile' => $paramsFile,
            'fileName' => $fileName,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'FmtDocSc' => $FmtDocSc,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlInfoComplType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $infras = $this->getDoctrine()->getRepository(TrInfRech::class)->findby([],['lbInfRech' => 'DESC']);

        $choixPc = $this->getDoctrine()->getRepository(TrInfo::class)->find('CHOIX_PC');
        $choixPc = ($choixPc)? $choixPc->getLbInfo() : '';
        $choixIr = $this->getDoctrine()->getRepository(TrInfo::class)->find('CHOIX_IR');
        $choixIr = ($choixIr)? $choixIr->getLbInfo() : '';
        $choixCo = $this->getDoctrine()->getRepository(TrInfo::class)->find('CHOIX_CO');
        $choixCo = ($choixCo)? $choixCo->getLbInfo() : '';


        $NbPolCompMax = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_POL_COMP_MAX']);
        $NbPolCompMax = ($NbPolCompMax)? $NbPolCompMax->getLbValeur() : 4;
        $NbStructRechMax = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_STRUCT_RECH_MAX']);
        $NbStructRechMax = ($NbStructRechMax)? $NbStructRechMax->getLbValeur() : 4;
        $NbCOMax = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_CO_FI_RECH_MAX']);
        $NbCOMax = ($NbCOMax)? $NbCOMax->getLbValeur() : 4;

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlInfoComplType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'labellisation' => $tgProjet->getBlDemLabel(),
            'infras' => $infras,
            'CHOIX_PC' => $choixPc,
            'CHOIX_IR' => $choixIr,
            'CHOIX_CO' => $choixCo,
            'NbPolCompMax' => $NbPolCompMax,
            'NbStructRechMax' => $NbStructRechMax,
            'NbCOMax' => $NbCOMax,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlResumeType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $tgResume = $this->getDoctrine()->getRepository(TgResume::class)->findby(['idProjet' => $tgProjet->getIdProjet()]);
        $tgResumeFr='';$tgResumeEn='';
        foreach ($tgResume as $resume) {
            if('Français' === $resume->getIdLangue()->getLbLangue()) $tgResumeFr = $resume->getLbTexte()?:'';
            if('Anglais' === $resume->getIdLangue()->getLbLangue()) $tgResumeEn = $resume->getLbTexte()?:'';
        }

        $tgBlock = $this->tgBlocRepository->findOneBy(['idBloc' => $idBloc]);
        // $idInfraFinancement = ($tgProjet->getIdInfraFi() !== null && 'BlInstFiType' === $tgBlock->getClassName()) ? $tgProjet->getIdInfraFi()->getIdInstFi(): '';

        $resume_ver_req = $this->getDoctrine()->getRepository(TrInfo::class)->find('RESUME_VER_REQ');
        $resume_ver_req = ($resume_ver_req)? $resume_ver_req->getLbInfo() : '';
        $nb_car_max_fr = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_CAR_MAX_FR']);
        $nb_car_max_fr = ($nb_car_max_fr)? $nb_car_max_fr->getLbValeur() : '';
        $nb_car_max_en = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_CAR_MAX_EN']);
        $nb_car_max_en = ($nb_car_max_en)? $nb_car_max_en->getLbValeur() : '';

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlResumeType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'resumeFr' => $tgResumeFr,
            'resumeEn' => $tgResumeEn,
            'idProjet' => $tgProjet->getIdProjet(),
            'errors' => $this->session->get("globalValidationActivated"),
            'resume_ver_req' => $resume_ver_req,
            'nb_car_max_fr' => $nb_car_max_fr,
            'nb_car_max_en' => $nb_car_max_en,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);
    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlMotCleCesType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $params = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_MC_CES_MAX']);
        $NbCesMax = ($params)? $params->getLbValeur(): 3;

        $idProjet = $tgProjet->getIdProjet()?: null;

        if (!$idComite) $this->session->getFlashBag()->add('infos', $this->translator->trans('bloc.mcces.idcomite.project'));

        return $this->render('bloc/BlMotCleCesType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'labellisation' => $tgProjet->getBlDemLabel(),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'NbCesMax' => $NbCesMax,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlMotCleErcType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $CatMcErcs = $this->getDoctrine()->getRepository('App:TrCategorieErc')->retrieveHydratedMcErc($idAppel)? : null;

        $NbErcMax = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['idAppel' => $idAppel, 'lbCode' => 'NB_MC_ERC_MAX']);
        $NbErcMax = ($NbErcMax)? $NbErcMax->getLbValeur() : 4;

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlMotCleErcType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'labellisation' => $tgProjet->getBlDemLabel(),
            'CatMcErcs' => $CatMcErcs,
            'tgMcSelected' =>$tgProjet->getIdMcErc(),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'NbErcMax' => $NbErcMax,
            'idProjet' => $idProjet

        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlMotCleLibreType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $tgMcLibre = $this->getDoctrine()->getRepository(TgMcLibre::class)->findby(['idProjet' => $tgProjet->getIdProjet()]);

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/BlMotCleLibreType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'tgMcLibre' => $tgMcLibre,
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'idProjet' => $tgProjet->getIdProjet(),
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingNonSouhaiteType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);

        $nonsouhaite =  $this->getDoctrine()->getRepository(TgNonSouhaite::class)->findbyProjet($tgProjet->getIdProjet());

        $idProjet = $tgProjet->getIdProjet()?: null;

        return $this->render('bloc/NonSouhaiteType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'nonsouhaite' => $nonsouhaite,
            'idProjet' => $tgProjet->getIdProjet(),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'idProjet' => $idProjet
        ]);

    }

    /**
     * @param Form $form
     * @param int $idBloc
     * @param $idComite
     * @param int $idFormulaire
     * @param Request $request
     * @param TgProjet $tgProjet
     * @param TgComite $tgComite
     * @param TgFormulaire $tgFormulaire
     * @param array $listBlocs
     * @param int $nextBlock
     * @param $previousBlock
     * @param int $idAppel
     * @param $projetName
     * @param $lbAppel
     * @param $idInfraFi
     * @param $listePartenaires
     * @param $blocValidation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    private function processingBlPartenariatType(
        Form $form,
        int $idBloc,
        $idComite,
        int $idFormulaire,
        Request $request,
        TgProjet $tgProjet,
        TgComite $tgComite,
        TgFormulaire $tgFormulaire,
        array $listBlocs,
        int $nextBlock,
        $previousBlock,
        int $idAppel,
        $projetName,
        $lbAppel,
        $idInfraFi,
        $listePartenaires,
        $blocValidation
    ){
        $tgAppel = $this->getDoctrine()->getRepository(TgAppelProj::class)->findOneby(['idAppel' =>$idAppel]);
        $idPhase =  $this->getDoctrine()->getRepository(TgPhase::class)->findOneby(['idPhase' =>$tgAppel->getNiveauEnCours()->getIdPhase()])->getIdPhaseRef()->getIdPhaseRef()?:1;
        if(1 === $idPhase){
            $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ', 'ROLE_RE_SCI', 'ROLE_RE_ADM'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET or ROLE_RE_SCI');
        } else {
            $this->denyAccessUnlessGranted(['ROLE_PORTEUR_PROJET','ROLE_COORD_PROJ'] , null, 'User tried to access a page without having ROLE_PORTEUR_PROJET');
        }

        $bloc = $this->getDoctrine()->getRepository(TgBloc::class)->findOneBy(['className' => "BlInstFiType"]);
        $blocId = ($bloc)? $bloc->getIdBloc() : 1;
        if(!$tgProjet->getIdInfraFi()) return $this->redirectToRoute('bloc', ['tgAppelProj' => $idAppel, 'tgFormulaire' => $idFormulaire, 'tgBloc' => $blocId]);


        $partenaires =  ($request->query->get('p')) ?
            $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idPartenaire' => $request->query->get('p')])
            : $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet()], ['idPartenaire' => 'DESC']);


        if($partenaires) {
            foreach ($partenaires as $part) {
                switch ($part->getTypPart()->getTypPart()) {
                    case 'PUF':
                        $listePartenaires[$part->getIdPartenaire()] = ($part->getHebergeur()) ? $part->getHebergeur()->getLbNomFr() : null;
                        break;
                    case 'PRF':
                        $listePartenaires[$part->getIdPartenaire()] = ($part->getTutGest()) ? $part->getTutGest()->getLbNomFr() : null;
                        break;
                    case 'ETR':
                        $listePartenaires[$part->getIdPartenaire()] = ($part->getLaboratoire()) ? $part->getLaboratoire()->getLbNomFr() : null;
                        break;
                }
            }
        }

        $partPuf = $this->createForm(BlPartenairePufType::class, $tgProjet);
        $partPrf = $this->createForm(BlPartenairePrfType::class, $tgProjet);

        $options = array('hidden' => array(), 'op_disabled' => array());
        foreach ($partPuf->all() as $key=>$fields) {
            $options['hidden'][$key] = $this->formulaireService->manageFormPhaseVisibility($idAppel, $idPhase, null, $key, $options);
            $options['op_disabled'][$key] = $this->formulaireService->manageFormPhasePermissions($idAppel, $idPhase, null, $key, $options);
        }
        $partenairePuf = $this->createForm(BlPartenairePufType::class, $tgProjet, $options);

        $options = array('hidden' => array(), 'op_disabled' => array());
        foreach ($partPrf->all() as $key=>$fields) {
            $options['hidden'][$key] = $this->formulaireService->manageFormPhaseVisibility($idAppel, $idPhase, null, $key, $options);
            $options['op_disabled'][$key] = $this->formulaireService->manageFormPhasePermissions($idAppel, $idPhase, null, $key, $options);
        }
        $partenairePrf = $this->createForm(BlPartenairePrfType::class, $tgProjet, $options);

        $partenaireErt = $this->createForm(BlPartenaireEtrType::class, $tgProjet);

        $trPaysFr = $this->getDoctrine()->getRepository(TrPays::class)->findOneby(['alpha2' => 'FR'])->getCdPays()? : '';

        // find coordinateur FR & coordinateur Etr
        $coordFr = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $tgProjet->getIdProjet(), 'cdPays' => $trPaysFr])?:'';

        $coordEtr = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->getCoordEtrByProjet($tgProjet->getIdProjet(), $trPaysFr)?:'';

        $typParts=array();
        foreach ( $partenaires as $parts) {
            $typParts[] = $parts->getTypPart()->getTypPart();
        }
        $typParts = array_unique($typParts);
        $trTypePart =  $this->getDoctrine()->getRepository(TrTypePart::class)->findby(array('typPart' => $typParts));

        $participantsForm = $this->createForm(BlParticipantsType::class);
        $typPartenaires =  $this->getDoctrine()->getRepository(TrTypePart::class)->findAll();

        $idProjet = $tgProjet->getIdProjet()?: null;

        $access_respsc = $this->container->get('security.authorization_checker')->isGranted('ROLE_RE_SCI');
        $access_respad = $this->container->get('security.authorization_checker')->isGranted('ROLE_RE_ADM');

        return $this->render('bloc/BlPartenariatType.html.twig', [
            'bloc' => $form->createView(),
            'list_bloc' => $listBlocs,
            'formulaire' => $tgFormulaire,
            'idComite' => $idComite,
            'idBloc' => $idBloc,
            'previousblock' => $previousBlock,
            'errors' => $this->session->get("globalValidationActivated"),
            'idProjet' => $tgProjet->getIdProjet(),
            'idAppel' => $idAppel,
            'projet' => $projetName,
            'typParts' => $trTypePart,
            'partenaires' => $partenaires,
            'participantsForm' =>$participantsForm->createView(),
            'lbAppel' => $lbAppel,
            'id_instrument' => $idInfraFi,
            'listePartenaires' => $listePartenaires,
            'blocValidation' => $blocValidation,
            'typPartenaires' => $typPartenaires,
            'partenairePuf' => $partenairePuf->createView(),
            'partenairePrf' => $partenairePrf->createView(),
            'partenaireEtr' => $partenaireErt->createView(),
            'coordFr' => $coordFr,
            'coordsEtr' => $coordEtr,
            'idPhase' => $idPhase,
            'idProjet' => $idProjet,
            'access_respsc' => $access_respsc,
            'access_respad' => $access_respad
        ]);

    }

    /**
     * @Route("/rnsrsearch/", name="rnsr_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxRnsrIds(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $rnsr = $request->request->get('search');

            $rnsrs =  $this->referentielService->getRnsrIds($rnsr);

            $response = new Response(json_encode($rnsrs));

            return $response;
        }
    }

    /**
     * @Route("/infornsr/", name="info_rnsr")
     * @param Request $request
     * @return Response
     */
    public function ajaxDatasRnsr(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $rnsr = $request->request->get('rnsr');

            $DatasRnsr =  $this->referentielService->getInfoRnsr($rnsr);

            $response = new Response(json_encode($DatasRnsr));

            return $response;
        }
    }

    /**
     * @Route("/etabsearch/", name="etab_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxListeEtabs(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $rnsr = $request->request->get('rnsr');

            $etabs =  $this->referentielService->getListeTutelles($rnsr);

            $response = new Response(json_encode($etabs));

            return $response;
        }
    }

    /**
     * @Route("/delegsearch/", name="deleg_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxListeDelegs(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $cleetab = $request->request->get('cleetab');

            $etabs =  $this->referentielService->getListeDelegations($cleetab);

            $response = new Response(json_encode($etabs));

            return $response;
        }
    }

    /**
     * @Route("/tutsearch/", name="tut_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxTutSearch(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $cleetab = $request->request->get('cleetab');

            $etabs =  $this->referentielService->getInfosTutelles($cleetab);

            $response = new Response(json_encode($etabs));

            return $response;
        }
    }

    /**
     * @Route("/siretsearch/", name="siret_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxListeSirets(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $siret = $request->request->get('siret');

            $sirets =  $this->referentielService->getListeSirets($siret);

            $response = new Response(json_encode($sirets));

            return $response;
        }
    }

    /**
     * @Route("/siretdatasearch/", name="siretdata_search")
     * @param Request $request
     * @return Response
     */
    public function ajaxDatasSiret(Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $siret = $request->request->get('siret');

            $DatasSiret =  $this->referentielService->getDatasSiret($siret);

            $response = new Response(json_encode($DatasSiret));

            return $response;
        }
    }

    /**
     * @Route("/tglibre/{idProjet}", name="tg_mc_libre_add")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxTgMcLibreAdd(int $idProjet, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);

            $lbNomFr = $request->request->get('lbNomFr');
            $lbNomEn = $request->request->get('lbNomEn');

            $tgMcLibre = new TgMcLibre();
            $tgMcLibre->setIdProjet($tgProjet);
            $tgMcLibre->setLbNom($lbNomFr);
            $tgMcLibre->setLbNomEn($lbNomEn);

            $errorsForm =  $this->validator->validate($tgMcLibre, null, 'BlMotCleLibreType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $errorsForm
                )));

            } else {

                $this->formulaireService->TgMcLibre($tgProjet, $lbNomFr, $lbNomEn);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/resume/{idProjet}", name="enreg_resume")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxResume(int $idProjet, Request $request)
    {
        $this->session->start();
        $this->session->remove("globalValidationActivated");

        $resumeFr = $request->request->get('resume_fr');
        $resumeEn = $request->request->get('resume_en');

        $tgProjet = $this->getDoctrine()->getRepository(TgProjet::class)->findOneby(['idProjet' => $idProjet]);

        $trlangueFr = $this->getDoctrine()->getRepository(TrLangue::class)->findOneby(['lbLangue' => 'FR'])? : new TrLangue();
        $trlangueEn = $this->getDoctrine()->getRepository(TrLangue::class)->findOneby(['lbLangue' => 'EN'])? : new TrLangue();

        $tgResume = $this->getDoctrine()->getRepository(TgResume::class)->findOneby(['idProjet' => $idProjet])? : new TgResume();

        $this->formulaireService->resumeEnreg($tgResume, $resumeFr, $resumeEn, $tgProjet, $trlangueFr, $trlangueEn);

        $response = new Response(json_encode(array(
            'resume_fr' => $resumeFr,
            'resume_en' => $resumeEn,
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/coord/{idPartenaire}", name="enreg_coord")
     * @param TgPartenariat $idPartenaire
     * @param Request $request
     * @return Response
     */
    public function ajaxCoord(TgPartenariat $idPartenaire, Request $request)
    {

        $tgProjet = $this->getDoctrine()->getRepository(TgProjet::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet()])?: null;
        $RespSc = $this->getDoctrine()->getRepository(TgPersonne::class)->findOneby(['idPersonne' => $idPartenaire->getRespScient()])? : null;

        if("PRF" === $idPartenaire->getTypPart()->getTypPart()) $organisme = $idPartenaire->getTutGest();
        if("PUF" === $idPartenaire->getTypPart()->getTypPart()) $organisme = $idPartenaire->getHebergeur();
        if("ETR" === $idPartenaire->getTypPart()->getTypPart()) $organisme = $idPartenaire->getLaboratoire();

        $tgOrganisme = $this->getDoctrine()->getRepository(TgOrganisme::class)->findOneby(['idOrganisme' => $organisme])? : null;

        $cdPays = $this->getDoctrine()->getRepository(TrPays::class)->findOneby(['cdPays' => 250])? : null;
        if("ETR" === $idPartenaire->getTypPart()->getTypPart()) $cdPays = $this->getDoctrine()->getRepository(TrPays::class)->findOneby(['cdPays' => $tgOrganisme->getCdPays()])? : null;

        $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet(), 'cdPays' => $cdPays])? : null;

        $this->formulaireService->CoordinateursEnreg($tgProjet, $RespSc, $tgOrganisme, $cdPays, $coord);

        $response = new Response(json_encode(array(
            'result' => 1,
            'message' => 'ok',
            'data' => ''
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/nonsouhaiteadd/{idProjet}", name="non_souhaite_add")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxNonSouhaiteAdd(int $idProjet, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {

            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $courriel = $request->request->get('courriel');
            $organisme = $request->request->get('organisme');
            $lbMotif = $request->request->get('lbMotif');

            $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);

            $tgNonSouhaite = new TgNonSouhaite();
            $tgNonSouhaite->setNom($nom);
            $tgNonSouhaite->setPrenom($prenom);
            $tgNonSouhaite->setCourriel($courriel);
            $tgNonSouhaite->setOrganisme($organisme);
            $tgNonSouhaite->setLbMotif($lbMotif);

            $errorsForm =  $this->validator->validate($tgNonSouhaite, null, 'NonSouhaiteType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $errorsForm
                )));

            } else {

                $this->formulaireService->nonSouhaiteAdd($tgProjet, $request);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

    /**
     * @Route("/nonsouhaiteedit/{idNonSouhaite}", name="non_souhaite_edit")
     * @param int $idNonSouhaite
     * @param Request $request
     * @return Response
     */
    public function ajaxNonSouhaiteEdit(int $idNonSouhaite, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {

            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $courriel = $request->request->get('courriel');
            $organisme = $request->request->get('organisme');
            $lbMotif = $request->request->get('lbMotif');


            $tgNS = new TgNonSouhaite();
            $tgNS->setNom($nom);
            $tgNS->setPrenom($prenom);
            $tgNS->setCourriel($courriel);
            $tgNS->setOrganisme($organisme);
            $tgNS->setLbMotif($lbMotif);

            $errorsForm =  $this->validator->validate($tgNS, null, 'NonSouhaiteType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $errorsForm
                )));

            } else {

                $tgNonSouhaite = $this->nonSouhaiteRepository->findOneby(['idNonSouhaite' => $idNonSouhaite]);
                $this->formulaireService->nonSouhaiteEdit($tgNonSouhaite, $nom, $prenom, $courriel, $organisme, $lbMotif);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/nonsouhaitedel/{idProjet}/{idNonSouhaite}", name="non_souhaite_del")
     * @param int $idProjet
     * @param int $idNonSouhaite
     * @param Request $request
     * @return Response
     */
    public function ajaxNonSouhaiteDelete(int $idProjet, int $idNonSouhaite, Request $request)
    {
        $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);
        $tgNonSouhaite = $this->nonSouhaiteRepository->findOneby(['idNonSouhaite' => $idNonSouhaite]);

        $this->formulaireService->nonSouhaiteDel($tgProjet, $tgNonSouhaite);

        $response = new Response(json_encode(array(
            'idProjet' => $idProjet,
            'idNonSouahite' => $idNonSouhaite
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/mclibreedit/{idMcLibre}", name="mc_libre_edit")
     * @param int $idMcLibre
     * @param Request $request
     * @return Response
     */
    public function ajaxMcLibreEdit(int $idMcLibre, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $terme_fr = $request->request->get('lbNomFr');
            $terme_en = $request->request->get('lbNomEn');

            $tgMcLibre = new TgMcLibre();
            $tgMcLibre->setLbNom($terme_fr);
            $tgMcLibre->setLbNomEn($terme_en);

            $errorsForm =  $this->validator->validate($tgMcLibre, null, 'BlMotCleLibreType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $errorsForm
                )));

            } else {
                $tgMcLibre = $this->getDoctrine()->getRepository(TgMcLibre::class)->findOneby(['idMcLibre' => $idMcLibre]);
                $this->formulaireService->mcLibreEdit($tgMcLibre, $terme_fr, $terme_en);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/mcllibredel/{idProjet}/{idMcLibre}", name="mc_libre_del")
     * @param int $idProjet
     * @param int $idMcLibre
     * @param Request $request
     * @return Response
     */
    public function ajaxMcLibreDelete(int $idProjet, int $idMcLibre, Request $request)
    {
        $tgMcLibre = $this->getDoctrine()->getRepository(TgMcLibre::class)->findOneby(['idMcLibre' => $idMcLibre]);

        $this->formulaireService->mcLibreDel($tgMcLibre);

        $response = new Response(json_encode(array(
            'idProjet' => $idProjet,
            'idMcLibre' => $idMcLibre
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/partenairedel/{idPartenaire}", name="partenaire_del")
     * @param int $idPartenaire
     * @param Request $request
     * @return Response
     */
    public function ajaxPartenaireDelete(int $idPartenaire, Request $request)
    {
        $tgPartenaire = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenaire]);
        $tgCp = $this->getDoctrine()->getRepository(TgCoutPrev::class)->findOneby(['idPartenaire' => $idPartenaire])?:new TgCoutPrev();

        $this->formulaireService->partenaireDel($tgPartenaire, $tgCp);

        $response = new Response(json_encode(array(
            'idPartenaire' => $tgPartenaire
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/participantedit/{idParticipant}/{idPartenaire}", name="participant_edit")
     * @param TgPersonne $idParticipant
     * @param TgPartenariat $idPartenaire
     * @param Request $request
     * @return Response
     */
    public function ajaxParticipantEdit(TgPersonne $idParticipant,TgPartenariat $idPartenaire, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {

            $resp = $request->request->get('resp');
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $email = $request->request->get('mail');
            $orcid = $request->request->get('orcid');
            $trCiv = $this->getDoctrine()->getRepository(TrCivilite::class)->findOneby(['idCivilite' => $request->request->get('civ')])?: null;

            $tgPersonne = new TgPersonne();
            $tgPersonne->setLbNomUsage($nom);
            $tgPersonne->setLbPrenom($prenom);
            $tgPersonne->setIdCivilite($trCiv);
            //  $tgPersonne->setOrcid($orcid);
            $tgIdExt = new TgIdExternes();
            $tgIdExt->setNumIdentifiant($orcid);

            $tgAMail = new TgAdrMail();
            $tgAMail->setAdrMail($email);

            $errorsFormPers =  $this->validator->validate($tgPersonne, null, 'ParticipantType');
            $errorsFormMail =  $this->validator->validate($tgAMail, null, 'ParticipantType');
            $errorsFormOrcid =  $this->validator->validate($tgIdExt, null, 'ParticipantType');
            $errorsFormPers = $this->constraintViolationValidation($errorsFormPers);
            $errorsFormMail = $this->constraintViolationValidation($errorsFormMail);
            $errorsFormOrcid = $this->constraintViolationValidation($errorsFormOrcid);

            if ($errorsFormPers || $errorsFormMail || $errorsFormOrcid) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'dataPers' => $errorsFormPers,
                    'dataMail' => $errorsFormMail,
                    'dataOrcid' => $errorsFormOrcid
                )));

            } else {

                if("PRF" === $idPartenaire->getTypPart()->getTypPart()){
                    $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet(), 'idOrganisme' => $idPartenaire->getTutGest()])? : null;
                } else {
                    $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet(), 'idOrganisme' => $idPartenaire->getHebergeur()])? : null;
                }
                $typOrcid = $this->getDoctrine()->getRepository(TrTypIdExt::class)->findOneby(['lbNomFr' => 'ORCID']);

                if($resp) {
                    $tgUtilisateur = $this->getDoctrine()->getRepository(User::class)->findOneby(['emailCanonical' => strtolower($email)]);
                    $url = $request->headers->get('host');
                    $profil =  $this->getDoctrine()->getRepository(TrProfil::class)->findOneby(['idProfil' => 12]);
                    $this->formulaireService->participantEdit($idParticipant, $request, $trCiv, $idPartenaire, $coord, $typOrcid, $tgUtilisateur, $url, $profil);
                } else {
                    $this->formulaireService->participantEdit($idParticipant, $request, $trCiv, $idPartenaire, $coord, $typOrcid, null, null, null);
                }

                $response = new Response(json_encode(array(
                    'idParticipant' => $idParticipant,
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/participantdel/{idPersonne}/{idPartenaire}", name="participant_del")
     * @param int $idPersonne
     * @param int $idPartenaire
     * @param Request $request
     * @return Response
     */
    public function ajaxParticipantDelete(int $idPersonne, int $idPartenaire, Request $request)
    {
        $tgPersonne = $this->getDoctrine()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $idPersonne]);
        $tgPartenaire = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneBy(['idPartenaire' => $idPartenaire]);

        $tgMail = $this->getDoctrine()->getRepository(TgAdrMail::class)->findOneBy(['idPersonne' => $idPersonne]);

        $this->formulaireService->participantDel($tgPersonne, $tgPartenaire, $tgMail);

        $response = new Response(json_encode(array(
            'idPersonne' => $idPersonne,
            'idPartenaire' => $idPartenaire
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/participantadd/{idPartenaire}", name="participant_add")
     * @param TgPartenariat $idPartenaire
     * @param Request $request
     * @return Response
     */
    public function ajaxParticipantAdd(TgPartenariat $idPartenaire, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {

            $resp = $request->request->get('resp');
            $civ = $request->request->get('civ');
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $mail = $request->request->get('mail');
            $orcid = $request->request->get('orcid');

            $trCiv = $this->getDoctrine()->getRepository(TrCivilite::class)->findOneBy(['idCivilite' => $civ])? : null;

            $tgPersonne = new TgPersonne();
            $tgPersonne->setLbNomUsage($nom);
            $tgPersonne->setLbPrenom($prenom);
            $tgPersonne->setIdCivilite($trCiv);
            //$tgPersonne->setOrcid($orcid);
            $tgIdExt = new TgIdExternes();
            $tgIdExt->setNumIdentifiant($orcid);

            $tgAdrMail = new TgAdrMail();
            $tgAdrMail->setAdrMail($mail);

            $errorsFormPers =  $this->validator->validate($tgPersonne, null, 'ParticipantType');
            $errorsFormMail =  $this->validator->validate($tgAdrMail, null, 'ParticipantType');
            $errorsFormOrcid =  $this->validator->validate($tgIdExt, null, 'ParticipantType');
            $errorsFormPers = $this->constraintViolationValidation($errorsFormPers);
            $errorsFormMail = $this->constraintViolationValidation($errorsFormMail);
            $errorsFormOrcid = $this->constraintViolationValidation($errorsFormOrcid);

            if ($errorsFormPers || $errorsFormMail || $errorsFormOrcid) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'dataPers' => $errorsFormPers,
                    'dataMail' => $errorsFormMail,
                    'dataOrcid' => $errorsFormOrcid
                )));

            } else {

                if("PRF" === $idPartenaire->getTypPart()->getTypPart()){
                    $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet(), 'idOrganisme' => $idPartenaire->getTutGest()])? : null;
                } else {
                    $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idPartenaire->getIdProjet(), 'idOrganisme' => $idPartenaire->getHebergeur()])? : null;
                }
                $typOrcid = $this->getDoctrine()->getRepository(TrTypIdExt::class)->findOneby(['lbNomFr' => 'ORCID']);

                if($resp) {
                    $tgUtilisateur = $this->getDoctrine()->getRepository(User::class)->findOneby(['emailCanonical' => strtolower($mail)]);
                    $url = $request->headers->get('host');
                    $profil =  $this->getDoctrine()->getRepository(TrProfil::class)->findOneby(['idProfil' => 12]);
                    $this->formulaireService->participantAdd($idPartenaire, $trCiv, $nom, $prenom, $mail, $orcid, $resp, $coord, $typOrcid, $tgUtilisateur, $url, $profil);
                } else {
                    $this->formulaireService->participantAdd($idPartenaire, $trCiv, $nom, $prenom, $mail, $orcid, $resp, $coord, $typOrcid, null, null, null);
                }

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/coutprev/{idPartenariat}", name="coutPrev")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxCoutPrev(int $idPartenariat, Request $request)
    {
        $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);
        $tgCoutPrev = $this->getDoctrine()->getRepository(TgCoutPrev::class)->findOneby(['idPartenaire' => $idPartenariat]);

        $response = new Response(json_encode(array(
            'ct_personnels_permanents'=>  ($tgCoutPrev)? $tgCoutPrev->getMntPersPerm() :0,
            'ct_personnels_non_permanents_ss_fin'=> ($tgCoutPrev)? $tgCoutPrev->getMntPersNpNf() :0,
            'ct_personnels_non_permanents'=> ($tgCoutPrev)? $tgCoutPrev->getMntPersNp() :0,
            'ct_decharge'=> ($tgCoutPrev)? $tgCoutPrev->getMntDechEns() :0,
            'ct_instruments'=> ($tgCoutPrev)? $tgCoutPrev->getMntInstMat() :0,
            'ct_batiments'=> ($tgCoutPrev)? $tgCoutPrev->getMntBatTer() :0,
            'ct_prestation_service'=> ($tgCoutPrev)? $tgCoutPrev->getMntPrest() :0,
            'ct_frais_gen'=> ($tgCoutPrev)? $tgCoutPrev->getMntFraisG() :0,
            'pt_personnels_permanents'=> ($tgCoutPrev)? $tgCoutPrev->getMntPersPermMois() :0,
            'pt_personnels_non_permanents_ss_fin'=> ($tgCoutPrev)? $tgCoutPrev->getMntPersNpNfMois() :0,
            'pt_personnels_non_permanents'=> ($tgCoutPrev)? $tgCoutPrev->getMntPersNpMois() :0,
            'pt_decharge'=> ($tgCoutPrev)? $tgCoutPrev->getMntDechEnsMois() :0,
            'taux_aide_dde'=> ($tgPartenariat)? $tgPartenariat->getTxAide() :0,
            'mnt_aide_dde'=> ($tgPartenariat)? $tgPartenariat->getMntAide() :0,
            'taux_frais_pers'=> ($tgPartenariat)? $tgPartenariat->getTxFraisPers() :0,
            'autres_dep'=> ($tgPartenariat)? $tgPartenariat->getAutresDep() :0,
            'taux_frais_env'=> ($tgPartenariat)? $tgPartenariat->getTxFraisEnv() :0,
            'idPartenaire'=> ($tgCoutPrev)? $tgCoutPrev->getIdPartenaire() :null
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/coutprevedit/{idPartenariat}", name="cout_prev_edit")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxCoutPrevEdit(int $idPartenariat, Request $request)
    {
        $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);
        $tgCoutPrev = $this->getDoctrine()->getRepository(TgCoutPrev::class)->findOneby(['idPartenaire' => $idPartenariat])?: new TgCoutPrev();

        $errorsForm = array(); $errors= '';

        $taux_aide_dde = ($request->request->get('taux_aide_dde')<=0)? array("field"=>"tad", "message"=>"Taux d'aide demandé doit être renseigné"): array();
        $mnt_aide_dde = ($request->request->get('mnt_aide_dde')<=0)? array("field"=>"total_ad", "message"=>"Montant d'aide demandé doit être renseigné"): array();
        $taux_frais_env=array();$taux_frais_pers=array(); $autres_dep=array();
        if ("PUF" === $tgPartenariat->getTypPart()->getTypPart()) {
            $taux_frais_env = ($request->request->get('taux_frais_env') <= 0 || $request->request->get('taux_frais_env') > 8) ? array("field" => "tfe", "message" => "Taux de frais d'environnement doit être renseigné (taux entre 1% et 8%)") : array();
        } elseif ("PRF" === $tgPartenariat->getTypPart()->getTypPart()) {
            $taux_frais_pers = ($request->request->get('taux_frais_pers') <= 0 || $request->request->get('taux_frais_pers') > 68) ? array("field" => "tfp", "message" => "Taux de frais personnel doit être renseigné (taux entre 1% et 68%)") : array();
            $autres_dep = ($request->request->get('autres_dep') <= 0 || $request->request->get('autres_dep') > 7) ? array("field" => "adep", "message" => "Autres dépenses doit être renseignées (taux entre 1% et 7%)") : array();

        }
        if($taux_aide_dde || $mnt_aide_dde || $taux_frais_pers || $autres_dep || $taux_frais_env) $errors=1;
        $errorsForm = array($taux_aide_dde, $mnt_aide_dde, $taux_frais_pers, $autres_dep, $taux_frais_env);
        if ($errors) {
            $response = new Response(json_encode(array(
                'result' => 0,
                'message' => 'Invalid form',
                'data' => $errorsForm
            )));

        } else {

            $this->formulaireService->coutPrevEdit($tgPartenariat, $tgCoutPrev, $request);

            $response = new Response(json_encode(array(
                'result' => 1,
                'message' => 'ok',
                'data' => ''
            )));

        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/pufadd/{idProjet}", name="puf_add")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxPufAdd(int $idProjet, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $trRnsr = new TrRnsr();
            $trRnsr->setCdRnsr($request->request->get('rnsr'));
            $trSiret = new TrSiret();
            $trSiret->setSiret($request->request->get('siret'));

            $tgOrganisme = new TgOrganisme();
            $tgOrganisme->setCdRnsr($trRnsr);
            $tgOrganisme->setSiret($trSiret);
            $tgOrganisme->setLbNomFr($request->request->get('name_tut_heb'));

            $errorsForm =  $this->validator->validate($tgOrganisme, null, 'PartenaireType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if(!$errorsForm) {
                $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);
                $typPart = $this->getDoctrine()->getRepository(TrTypePart::class)->findOneBy(['typPart' => $request->request->get('type_part')]);
                $trPays = $this->getDoctrine()->getRepository(TrPays::class)->findOneBy(['cdPays' => 250]) ?: null;

                $tutLib = $this->referentielService->getLibelle($request->request->get('name_tut_heb')) ?: null;
                $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idProjet, 'cdPays' => $trPays->getCdPays()]) ?: null;

                $tgPartenariat = $this->formulaireService->pufAdd($tgProjet, $request, $typPart, $trPays, $coord, $tutLib);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => '',
                    'partenaireId' => $tgPartenariat->getIdPartenaire()
                )));
            } else {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'errors' => $errorsForm,
                    'data' => ''
                )));
            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/pufedit/{idPartenariat}", name="puf_edit")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxPufEdit(int $idPartenariat, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $trRnsr = new TrRnsr();
            $trRnsr->setCdRnsr($request->request->get('rnsr'));
            $trSiret = new TrSiret();
            $trSiret->setSiret($request->request->get('siret'));

            $tgOrganisme = new TgOrganisme();
            $tgOrganisme->setCdRnsr($trRnsr);
            $tgOrganisme->setSiret($trSiret);
            $tgOrganisme->setLbNomFr($request->request->get('name_tut_heb'));

            $errorsForm = $this->validator->validate($tgOrganisme, null, 'PartenaireType');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if (!$errorsForm) {
                $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);

                $mailDirectLab = '';
                if ($tgPartenariat->getDirLabo()) {
                    foreach ($tgPartenariat->getDirLabo()->getIdAdrMail() as $mails) {
                        $mailDirectLab = ($mails->getAdrMail()) ?: '';
                    }
                }

                $mailGestadmin = '';
                if ($tgPartenariat->getGestAdm()) {
                    foreach ($tgPartenariat->getGestAdm()->getIdAdrMail() as $mails) {
                        $mailGestadmin = ($mails->getAdrMail()) ?: '';
                    }
                }

                $trPays = ($this->getDoctrine()->getRepository(TrPays::class)->findOneby(['cdPays' => 250])) ?: '';
                $tgAdrMailDirectLab = $this->getDoctrine()->getRepository(TgAdrMail::class)->findOneby(['adrMail' => $mailDirectLab]) ?: null;
                $tgAdrMailGestAdmin = $this->getDoctrine()->getRepository(TgAdrMail::class)->findOneby(['adrMail' => $mailGestadmin]) ?: null;

                $this->formulaireService->pufEdit($tgPartenariat, $tgAdrMailDirectLab, $tgAdrMailGestAdmin, $trPays, $request);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            } else {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'errors' => $errorsForm,
                    'data' => ''
                )));
            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/pufshow/{idPartenariat}", name="pufShow")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxPufShow(int $idPartenariat, Request $request)
    {
        $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);

        $mailDirectLab='';
        if($tgPartenariat->getDirLabo()){
            foreach($tgPartenariat->getDirLabo()->getIdAdrMail() as $mails) {
                $mailDirectLab = ($mails->getAdrMail())?: '';
            }
        }

        $mailGestadmin= '';
        if($tgPartenariat->getGestAdm()) {
            foreach ($tgPartenariat->getGestAdm()->getIdAdrMail() as $mails) {
                $mailGestadmin = ($mails->getAdrMail()) ?: '';
            }
        }

        $delegLb='';
        $cleetab =  $this->referentielService->getCleWithSiret($tgPartenariat->getHebergeur()->getSiret()->getSiret())?: '';
        $listeDeleg = ($cleetab) ? $this->referentielService->getListeDelegations($cleetab):null;
        if($listeDeleg) {
            foreach ($listeDeleg as $deleg){
                if($deleg['id'] == $tgPartenariat->getLbDeleguation()) $delegLb  = $deleg['text'];
            }
        }

        $rib = ($tgPartenariat->getIdCompte())? $this->formulaireService->decrypt($tgPartenariat->getIdCompte()->getRib()) : null;
        $iban = ($tgPartenariat->getIdCompte())? $this->formulaireService->decrypt($tgPartenariat->getIdCompte()->getIban()) : null;
        $banque = ($tgPartenariat->getIdCompte())? $tgPartenariat->getIdCompte()->getBanque() : null;

        $response = new Response(json_encode(array(
            'rnsr_puf' =>  $tgPartenariat->getHebergeur()->getCdRnsr()->getCdRnsr(),
            'siret_puf' => $tgPartenariat->getHebergeur()->getSiret()->getSiret(),
            'delegation_puf' => $delegLb,
            'name_tut_heb_puf' =>  $tgPartenariat->getHebergeur()->getLbNomFr(),
            'laboratoire' => $tgPartenariat->getHebergeur()->getCdRnsr()->getLbLaboratoire(),
            'code_unite_puf' => $tgPartenariat->getHebergeur()->getSiret()->getCodeUnite(),
            'adress_tut_heb_puf' => ($tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse())? $tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getLbAdresse():'',
            'compl_adress_tut_heb_puf' => ($tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse())? $tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getLbComplAdresses():'',
            'postal_code_tut_heb_puf' => ($tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse())? $tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getCd():'',
            'city_tut_heb_puf' => ($tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse())?  $tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getVille():'',
            'country_tut_heb_puf' => ($tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getCdPays())? $tgPartenariat->getHebergeur()->getCdRnsr()->getIdAdresse()->getCdPays()->getLbPays():'',
            'firstname_direct_lab_puf' => ($tgPartenariat->getDirLabo())? $tgPartenariat->getDirLabo()->getLbPrenom():'',
            'lastname_direct_lab_puf' =>  ($tgPartenariat->getDirLabo())? $tgPartenariat->getDirLabo()->getLbNomUsage():'',
            'courriel_direct_lab_puf' => $mailDirectLab,

            'siret_tut_gest_puf' =>  ($tgPartenariat->getTutGest())? $tgPartenariat->getTutGest()->getSiret()->getSiret():'',
            'name_tut_gest_puf' => ($tgPartenariat->getTutGest())? $tgPartenariat->getTutGest()->getLbNomFr():'',
            'adress_tut_gest_puf' => ($tgPartenariat->getTutGest() && $tgPartenariat->getTutGest()->getSiret()->getIdAdresse())?  $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getLbAdresse():'',
            'compl_adress_tut_gest_puf' => ($tgPartenariat->getTutGest() && $tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getLbComplAdresses():'',
            'postal_code_tut_gest_puf' => ($tgPartenariat->getTutGest() && $tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getCd():'',
            'city_tut_gest_puf' =>   ($tgPartenariat->getTutGest() && $tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getVille():'',
            'country_tut_gest_puf' =>  ($tgPartenariat->getTutGest() && $tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getCdPays()->getLbPays():'',
            'banque_tut_g_puf' =>  $banque,
            'rib_tut_g_puf' =>  $rib,
            'iban_tut_g_puf' =>  $iban,

            'lastname_gest_admin_puf' => ($tgPartenariat->getGestAdm())? $tgPartenariat->getGestAdm()->getLbNomUsage():'',
            'firstname_gest_admin_puf' => ($tgPartenariat->getGestAdm())? $tgPartenariat->getGestAdm()->getLbPrenom():'',
            'mail_gest_admin_puf' => $mailGestadmin,

            'firstname_rep_juridique' => ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getLbNomUsage():'',
            'lastname_rep_juridique' => ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getLbPrenom():'',
            'function_rep_juridique' => ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getFonction():'',
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/prfadd/{idProjet}", name="prf_add")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxPrfAdd(int $idProjet, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $trSiret = new TrSiret();
            $trSiret->setSiret($request->request->get('siret_tut_gest'));
            $tgOrganisme = new TgOrganisme();
            $tgOrganisme->setSiret($trSiret);
            $tgOrganisme->setLbNomFr($request->request->get('name_tut_gest'));

            $errorsForm =  $this->validator->validate($tgOrganisme, null, 'PartenaireTypePrf');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if(!$errorsForm) {
                $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);
                $typPart = $this->getDoctrine()->getRepository(TrTypePart::class)->findOneBy(['typPart' => $request->request->get('type_part')]);
                $trPays = ($this->getDoctrine()->getRepository(TrPays::class)->findOneby(['cdPays' => 250])) ?: '';
                $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idProjet, 'cdPays' => $trPays->getCdPays()]) ?: null;

                $tgPartenariat = $this->formulaireService->prfAdd($tgProjet, $request, $typPart, $trPays, $coord);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => '',
                    'partenaireId' => $tgPartenariat->getIdPartenaire()
                )));

            } else {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'errors' => $errorsForm,
                    'data' => ''
                )));
            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/prfedit/{idPartenariat}", name="prf_edit")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxPrfEdit(int $idPartenariat, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {
            $trSiret = new TrSiret();
            $trSiret->setSiret($request->request->get('siret_tut_gest'));
            $tgOrganisme = new TgOrganisme();
            $tgOrganisme->setSiret($trSiret);
            $tgOrganisme->setLbNomFr($request->request->get('name_tut_gest'));

            $errorsForm =  $this->validator->validate($tgOrganisme, null, 'PartenaireTypePrf');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if(!$errorsForm) {
                $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);

                $mailGestadmin = '';
                if ($tgPartenariat->getGestAdm()) {
                    foreach ($tgPartenariat->getGestAdm()->getIdAdrMail() as $mails) {
                        $mailGestadmin = ($mails->getAdrMail()) ?: '';
                    }
                }

                $tgAdrMailGestAdmin = $this->getDoctrine()->getRepository(TgAdrMail::class)->findOneby(['adrMail' => $mailGestadmin]) ?: new TgAdrMail();

                $trPays = $this->getDoctrine()->getRepository(TrPays::class)->findOneBy(['cdPays' => 250]) ?: null;

                $this->formulaireService->prfEdit($tgPartenariat, $request, $tgAdrMailGestAdmin, $trPays);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            } else {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'errors' => $errorsForm,
                    'data' => ''
                )));
            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/prfshow/{idPartenariat}", name="prfShow")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxPrfShow(int $idPartenariat, Request $request)
    {
        $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);

        $mailGestadmin= '';
        if($tgPartenariat->getGestAdm()) {
            foreach ($tgPartenariat->getGestAdm()->getIdAdrMail() as $mails) {
                $mailGestadmin = ($mails->getAdrMail()) ?: '';
            }
        }

        $rib = ($tgPartenariat->getIdCompte())? $this->formulaireService->decrypt($tgPartenariat->getIdCompte()->getRib()) : null;
        $iban = ($tgPartenariat->getIdCompte())? $this->formulaireService->decrypt($tgPartenariat->getIdCompte()->getIban()) : null;
        $banque = ($tgPartenariat->getIdCompte())? $tgPartenariat->getIdCompte()->getBanque() : null;

        $response = new Response(json_encode(array(
            'siret_tut_gest_prf' =>  $tgPartenariat->getTutGest()->getSiret()->getSiret(),
            'name_tut_gest_prf' => ($tgPartenariat->getTutGest())? $tgPartenariat->getTutGest()->getLbNomFr():'',
            'sigle_prf' => ($tgPartenariat->getTutGest())? $tgPartenariat->getTutGest()->getSiret()->getSigle():'',
            'adress_tut_gest_prf' => ($tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getLbAdresse():'',
            'compl_adress_tut_gest_prf' => ($tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getLbComplAdresses():'',
            'postal_code_tut_gest_prf' => ($tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getCd():'',
            'city_tut_gest_prf' =>   ($tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getVille():'',
            'country_tut_gest_prf' =>  ($tgPartenariat->getTutGest()->getSiret()->getIdAdresse())? $tgPartenariat->getTutGest()->getSiret()->getIdAdresse()->getCdPays()->getLbPays():'',
            'banque_tut_g_prf' =>  $banque,
            'rib_tut_g_prf' =>  $rib,
            'iban_tut_g_prf' =>  $iban,

            'firstname_rep_juridique'=> ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getLbNomUsage():'',
            'lastname_rep_juridique'=> ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getLbPrenom():'',
            'function_rep_juridique'=> ($tgPartenariat->getRepJuridique())? $tgPartenariat->getRepJuridique()->getFonction():'',

            'lastname_gest_admin_prf' => ($tgPartenariat->getGestAdm())? $tgPartenariat->getGestAdm()->getLbNomUsage():'',
            'firstname_gest_admin_prf' => ($tgPartenariat->getGestAdm())? $tgPartenariat->getGestAdm()->getLbPrenom():'',
            'mail_gest_admin_prf' => $mailGestadmin
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/etradd/{idProjet}", name="etr_add")
     * @param int $idProjet
     * @param Request $request
     * @return Response
     */
    public function ajaxEtrAdd(int $idProjet, Request $request)
    {
        if ( $request->isXmlHttpRequest() ) {

            $trPays = ($this->getDoctrine()->getRepository(TrPays::class)->findOneby(['cdPays' => $request->request->get('country')])) ?: null;

            $tgOrganisme = new TgOrganisme();
            $tgOrganisme
                ->setLbNomFr($request->request->get('name'))
                ->setCdPays($trPays)
                ->setVille($request->request->get('city'))
                ->setLbLaboratoire($request->request->get('laboratoire'))
            ;

            $errorsForm =  $this->validator->validate($tgOrganisme, null, 'PartenaireEtr');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'messageError' => $errorsForm,
                    'data' => ''
                )));

                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $tgProjet = $this->tgProjetRepository->findOneby(['idProjet' => $idProjet]);
            $typPart =  $this->getDoctrine()->getRepository(TrTypePart::class)->findOneBy(['typPart' => $request->request->get('type_part')]);
            $coord = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $idProjet, 'cdPays' => $trPays->getCdPays()])? : null;

            $tgPartenariat = $this->formulaireService->etrAdd($tgProjet, $tgOrganisme, $typPart, $trPays, $coord);

            $response = new Response(json_encode(array(
                'result' => 1,
                'message' => 'ok',
                'data' => '',
                'partenaireId' => $tgPartenariat->getIdPartenaire()
            )));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/etredit/{idPartenariat}", name="etr_edit")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxEtrEdit(int $idPartenariat, Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $trPays = ($this->getDoctrine()->getRepository(TrPays::class)->findOneby(['lbPays' => $request->request->get('country')])) ?: null;

            $tgOrganisme = new TgOrganisme();
            $tgOrganisme
                ->setLbNomFr($request->request->get('name'))
                ->setCdPays($trPays)
            ;

            $errorsForm =  $this->validator->validate($tgOrganisme, null, 'PartenaireEtr');
            $errorsForm = $this->constraintViolationValidation($errorsForm);

            if ($errorsForm) {

                $response = new Response(json_encode(array(
                    'result' => 0,
                    'messageError' => $errorsForm,
                    'data' => ''
                )));

                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);

            $this->formulaireService->etrEdit($tgPartenariat, $request, $trPays);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/etrshow/{idPartenariat}", name="etrShow")
     * @param int $idPartenariat
     * @param Request $request
     * @return Response
     */
    public function ajaxEtrShow(int $idPartenariat, Request $request)
    {
        $tgPartenariat = $this->getDoctrine()->getRepository(TgPartenariat::class)->findOneby(['idPartenaire' => $idPartenariat]);
        $response = new Response(json_encode(array(
            'name' =>  ($tgPartenariat->getLaboratoire())?$tgPartenariat->getLaboratoire()->getLbNomFr() : '',
            'laboratoire' =>  ($tgPartenariat->getLaboratoire())? $tgPartenariat->getLaboratoire()->getLbLaboratoire() : '',
            'city' =>  ($tgPartenariat->getLaboratoire())? $tgPartenariat->getLaboratoire()->getVille() : '',
            'country' => ($tgPartenariat->getLaboratoire())? $tgPartenariat->getLaboratoire()->getCdPays()->getLbPays() : ''
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/export/{tgProjet}", name="export")
     * @param TgProjet $tgProjet
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportAction(TgProjet $tgProjet)
    {
        $partenaires = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet()]) : '';

        $ProjetId = $tgProjet->getIdProjet()?: '';

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        $sheet->setTitle("My First Worksheet");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/syntheseProjet/';

        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . 'synthese_soumission_' . $ProjetId . '.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        // Create a Temporary file in the system
        $fileName = 'synthese_soumission_' . $ProjetId . '.xlsx';

        return $this->file($excelFilepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/export/{tgProjet}", name="export")
     * @param TgProjet $tgProjet
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcelAction(TgProjet $tgProjet)
    {
        $partenaires = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet()]) : '';
        $ProjetId = $tgProjet->getIdProjet()?: '';

        $poles = ''; $infras = ''; $cofis = '';
        foreach($tgProjet->getIdPoleComp() as $key=>$pole){
            $poles .= $pole->getLbPolComp(). ',';
        }
        foreach($tgProjet->getIdInfRech() as $key=>$infra){
            $infras .= $infra->getLbNomLong(). ',';
        }
        foreach($tgProjet->getIdCoFi() as $key=>$cofi){
            $cofis .= $cofi->getLbCoFi(). ',';
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $alph = array("0","A","B","C","D","E","F","G","H","I","J","K","L");
        $totalDatas = count($partenaires);

        $sheet
            ->setTitle("Identité du projet");
        $sheet
            ->setCellValue('A1', 'Acronyme')
            ->setCellValue('B1', 'Titre projet Fr')
            ->setCellValue('C1', 'Titre projet En')
            ->setCellValue('D1', 'Durée du projet')
            ->setCellValue('E1', 'Type recherche')
            ->setCellValue('F1', 'Instrument')
            ->setCellValue('G1', 'CES selectionné')
            ->setCellValue('H1', 'Montant provisionnel d’aide (en K€)')
            ->setCellValue('I1', 'Pôle de compétitivité')
            ->setCellValue('J1', 'Infrastructure de recherche')
            ->setCellValue('K1', 'Cofinanceur');

        $sheet
            ->setCellValue('A2', $tgProjet->getLbAcro())
            ->setCellValue('B2', $tgProjet->getLbTitreFr())
            ->setCellValue('C2', $tgProjet->getLbTitreEn())
            ->setCellValue('D2', $tgProjet->getNoDuree())
            ->setCellValue('E2', $tgProjet->getIdCatRd())
            ->setCellValue('F2', $tgProjet->getIdInfraFi())
            ->setCellValue('G2', $tgProjet->getIdComite())
            ->setCellValue('H2', $tgProjet->getMntAidePrev())
            ->setCellValue('I2', $poles)
            ->setCellValue('J2', $infras)
            ->setCellValue('K2', $cofis)
        ;

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(1);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->setTitle("Partenaires Publiques");
        $sheet

            ->setCellValue('A1', 'Tutelle Gestionnaire SIRET')
            ->setCellValue('B1', 'Tutelle Hébergeur RNSR')
            ->setCellValue('C1', 'Laboratoire')
            ->setCellValue('D1', 'Participants')
            ->setCellValue('E1', 'Montant d\'aide')
            ->setCellValue('F1', 'Taux d\'aide')
            ->setCellValue('G1', 'Resp. Scientifique')
            ->setCellValue('H1', 'Dir. Unité')
            ->setCellValue('I1', 'Resp. administratif - Nom')
            ->setCellValue('J1', 'Resp. administratif - Courriel')
        ;
        $partenairesPuf = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'PUF']) : '';

        foreach($partenairesPuf as $key=>$partenaire) {
            $key = $key + 2;

            $nbrParticipants = count($partenaire->getIdPersonne());
            if($partenaire->getTutGest())
                $sheet->setCellValue('A'.$key, $partenaire->getTutGest()->getSiret() . ' - ' . $partenaire->getTutGest()->getLbNomFr()) ? :  $sheet->setCellValue('A'.$key, '');
            if($partenaire->getHebergeur())
                $sheet->setCellValue('B'.$key, $partenaire->getHebergeur()->getCdRnsr() . ' - ' . $partenaire->getHebergeur()->getLbNomFr()) ? :  $sheet->setCellValue('B'.$key, '');

            $sheet
                ->setCellValue('C'.$key, $partenaire->getLaboratoire())
                ->setCellValue('D'.$key, $nbrParticipants)
                ->setCellValue('E'.$key, $partenaire->getMntAide())
                ->setCellValue('F'.$key, $partenaire->getTxAide())
                ->setCellValue('G'.$key, $partenaire->getRespScient())
                ->setCellValue('H'.$key, $partenaire->getRepJuridique());
            if($partenaire->getGestAdm())
                $sheet->setCellValue('I'.$key, $partenaire->getGestAdm()->getLbNomUsage()) ? :  $sheet->setCellValue('I'.$key, '');
            $mailGa ="";
            if($partenaire->getGestAdm()){
                foreach($partenaire->getGestAdm()->getIdAdrMail() as $mail) {
                    $mailGa = $mail->getAdrMail();
                }
                $sheet->setCellValue('J'.$key, $mailGa);
            }
        }

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(2);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->setTitle("Partenaires Privés");
        $sheet

            ->setCellValue('A1', 'Tutelle Gestionnaire SIRET')
            ->setCellValue('B1', 'Tutelle Hébergeur RNSR')
            ->setCellValue('C1', 'Laboratoire')
            ->setCellValue('D1', 'Participants')
            ->setCellValue('E1', 'Montant d\'aide')
            ->setCellValue('F1', 'Taux d\'aide')
            ->setCellValue('G1', 'Resp. Scientifique')
            ->setCellValue('H1', 'Dir. Unité')
            ->setCellValue('I1', 'Resp. administratif - Nom')
            ->setCellValue('J1', 'Resp. administratif - Courriel')
        ;
        $partenairesPrf = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'PRF']) : '';

        foreach($partenairesPrf as $key=>$partenaire) {
            $key = $key + 2;

            $nbrParticipants = count($partenaire->getIdPersonne());
            if($partenaire->getTutGest())
                $sheet->setCellValue('A'.$key, $partenaire->getTutGest()->getSiret() . ' - ' . $partenaire->getTutGest()->getLbNomFr()) ? :  $sheet->setCellValue('A'.$key, '');
            if($partenaire->getHebergeur())
                $sheet->setCellValue('B'.$key, $partenaire->getHebergeur()->getCdRnsr() . ' - ' . $partenaire->getHebergeur()->getLbNomFr()) ? :  $sheet->setCellValue('B'.$key, '');

            $sheet
                ->setCellValue('C'.$key, $partenaire->getLaboratoire())
                ->setCellValue('D'.$key, $nbrParticipants)
                ->setCellValue('E'.$key, $partenaire->getMntAide())
                ->setCellValue('F'.$key, $partenaire->getTxAide())
                ->setCellValue('G'.$key, $partenaire->getRespScient())
                ->setCellValue('H'.$key, $partenaire->getRepJuridique());
            if($partenaire->getGestAdm())
                $sheet->setCellValue('I'.$key, $partenaire->getGestAdm()->getLbNomUsage()) ? :  $sheet->setCellValue('I'.$key, '');


            $mailGa ="";
            if($partenaire->getGestAdm()){
                foreach($partenaire->getGestAdm()->getIdAdrMail() as $mail) {
                    $mailGa = $mail->getAdrMail();
                }
                //if(!isset($mailGa)) ? : $mailGa ="";
                $sheet->setCellValue('J'.$key, $mailGa);
            }
        }

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(3);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->setTitle("Partenaires Etrangers");
        $sheet

            ->setCellValue('A1', 'Tutelle Gestionnaire SIRET')
            ->setCellValue('B1', 'Tutelle Hébergeur RNSR')
            ->setCellValue('C1', 'Laboratoire')
            ->setCellValue('D1', 'Participants')
            ->setCellValue('E1', 'Montant d\'aide')
            ->setCellValue('F1', 'Taux d\'aide')
            ->setCellValue('G1', 'Resp. Scientifique')
            ->setCellValue('H1', 'Dir. Unité')
            ->setCellValue('I1', 'Resp. administratif - Nom')
            ->setCellValue('J1', 'Resp. administratif - Courriel')
        ;
        $partenairesEtr = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet(), 'typPart' => 'ETR']) : '';

        foreach($partenairesEtr as $key=>$partenaire) {
            $key = $key + 2;

            $nbrParticipants = count($partenaire->getIdPersonne());
            if($partenaire->getTutGest())
                $sheet->setCellValue('A'.$key, $partenaire->getTutGest()->getSiret() . ' - ' . $partenaire->getTutGest()->getLbNomFr()) ? :  $sheet->setCellValue('A'.$key, '');
            if($partenaire->getHebergeur())
                $sheet->setCellValue('B'.$key, $partenaire->getHebergeur()->getCdRnsr() . ' - ' . $partenaire->getHebergeur()->getLbNomFr()) ? :  $sheet->setCellValue('B'.$key, '');

            $sheet
                ->setCellValue('C'.$key, $partenaire->getLaboratoire())
                ->setCellValue('D'.$key, $nbrParticipants)
                ->setCellValue('E'.$key, $partenaire->getMntAide())
                ->setCellValue('F'.$key, $partenaire->getTxAide())
                ->setCellValue('G'.$key, $partenaire->getRespScient())
                ->setCellValue('H'.$key, $partenaire->getRepJuridique());
            if($partenaire->getGestAdm())
                $sheet->setCellValue('I'.$key, $partenaire->getGestAdm()->getLbNomUsage()) ? :  $sheet->setCellValue('I'.$key, '');
            $mailGa ="";
            if($partenaire->getGestAdm()){
                foreach($partenaire->getGestAdm()->getIdAdrMail() as $mail) {
                    $mailGa = $mail->getAdrMail();
                }
                $sheet->setCellValue('J'.$key, $mailGa);
            }
        }

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(4);
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->setTitle("Coûts prévisionnels");
        $sheet
            ->setCellValue('A1', 'Type partenaire')
            ->setCellValue('B1', 'Partenaire')
            ->setCellValue('C1', 'Personnel permanent')
            ->setCellValue('D1', 'Personnel non permanent financé')
            ->setCellValue('E1', 'Personnel non permanent non financé')
            ->setCellValue('F1', 'Décharge d\'enseignement')
            ->setCellValue('G1', 'Instrument / Matériel')
            ->setCellValue('H1', 'Bâtiments et terrains')
            ->setCellValue('I1', 'Prestation de services et de droit PI')
            ->setCellValue('J1', 'Taux d\'aide')
            ->setCellValue('K1', 'Aide demandée')
        ;


        foreach($partenairesEtr as $key=>$partenaire) {
            $key = $key + 2;

            $nbrParticipants = count($partenaire->getIdPersonne());

            $sheet
                ->setCellValue('A'.$key, $partenaire->getTypPart()->getLbNomFr());
            if($partenaire->getHebergeur())
                $sheet->setCellValue('B'.$key, $partenaire->getHebergeur()->getCdRnsr() . ' - ' . $partenaire->getHebergeur()->getLbNomFr()) ? :  $sheet->setCellValue('B'.$key, '');
            if($partenaire->getIdCoutPrv()) {
                $sheet
                    ->setCellValue('C'.$key, $partenaire->getIdCoutPrv()->getMntPersPerm())
                    ->setCellValue('D'.$key, $partenaire->getIdCoutPrv()->getMntPersNp())
                    ->setCellValue('E'.$key, $partenaire->getIdCoutPrv()->getMntPersNpNf())
                    ->setCellValue('F'.$key, $partenaire->getIdCoutPrv()->getMntDechEns())
                    ->setCellValue('G'.$key, $partenaire->getIdCoutPrv()->getMntInstMat())
                    ->setCellValue('H'.$key, $partenaire->getIdCoutPrv()->getMntBatTer())
                    ->setCellValue('I'.$key, $partenaire->getIdCoutPrv()->getMntPrest())
                    ->setCellValue('J'.$key, $partenaire->getTxAide())
                    ->setCellValue('K'.$key, $partenaire->getMntAide())
                ;
            }
        }


        $spreadsheet->setActiveSheetIndex(0);
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/syntheseProjet/';

        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . 'synthese_soumission_' . $ProjetId . '.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        // Create a Temporary file in the system
        $fileName = 'synthese_soumission_' . $ProjetId . '.xlsx';

        return $this->file($excelFilepath, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/exportpdf/{tgProjet}", name="export_pdf")
     * @param TgProjet $tgProjet
     */
    public function exportPdfAction(TgProjet $tgProjet)
    {

        $partenaires = ($tgProjet) ? $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $tgProjet->getIdProjet()]) : '';
        $ProjetId = $tgProjet->getIdProjet()?: '';

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('bloc/BlSynthesePdf.html.twig', [
            'title' => "Synthése du projet",
            'tgProjet' => $tgProjet,
            'partenaires' => $partenaires
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A2', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("synthese_soumission.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @param ConstraintViolationListInterface $validationErrors
     * @return array
     */
    private function constraintViolationValidation(ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($validationErrors as $key=> $violation) {
                $message = $violation->getMessage();
                if ("idMcCes[id_mc_ces]" == $violation->getPropertyPath()){
                    $message =  "Au moins un mot clé CES doit être renseigné";
                }
                if ("idMcErc[id_mc_erc]" == $violation->getPropertyPath()){
                    $message =  "Au moins un mot clé ERC doit être renseigné";
                }
                if ("idPoleComp[id_pole_comp]" == $violation->getPropertyPath()){
                    $message =  "Au moins un pôle de compétitivité doit être renseigné";
                }
                if ("idInfRech[id_inf_rech]" == $violation->getPropertyPath()) {
                    $message =  "Au moins une infrastructure de recherche doit être renseignée";
                }
                if ("idCoFi[id_co_fi]" == $violation->getPropertyPath()) {
                    $message =  "Au moins un cofinancement doit être renseigné";
                }

                $violations[] = [
                    'field' => $violation->getPropertyPath(),
                    'message'      => $message,
                ];
            }
            return $violations;
        }
    }

}
