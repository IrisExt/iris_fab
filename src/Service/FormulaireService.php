<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\TrGenre;
use App\Entity\TrProfil;
use App\Entity\TgAdrMail;
use App\Entity\TgAppelProj;
use App\Entity\TgBloc;
use App\Entity\TgCompteBancaire;
use App\Entity\TgCoordinationProj;
use App\Entity\TgCoutPrev;
use App\Entity\TgDocument;
use App\Entity\TgHabilitation;
use App\Entity\TgIdExternes;
use App\Entity\TgMcLibre;
use App\Entity\TgNonSouhaite;
use App\Entity\TgOrganisme;
use App\Entity\TgPartenariat;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Entity\TgResume;
use App\Entity\TrCivilite;
use App\Entity\TrLangue;
use App\Entity\TrTypePart;
use App\Entity\TrTypIdExt;
use App\Entity\User;
use App\Form\Blocs\Type\BlDocScientifiqueType;
use App\Form\Blocs\Type\BlIdentProjType;
use App\Form\Blocs\Type\BlInstFiType;
use App\Form\Blocs\Type\BlInfoComplType;
use App\Form\Blocs\Type\BlResumeType;
use App\Form\Blocs\Type\BlMotCleCesType;
use App\Form\Blocs\Type\BlMotCleLibreType;
use App\Form\Blocs\Type\NonSouhaiteType;
use App\Form\Blocs\Type\BlMotCleErcType;
use App\Form\Blocs\Type\BlPartenariatType;
use App\Form\Blocs\Type\BlSoumettreType;
use App\Form\Blocs\Type\BlSyntheseType;
use App\Repository\HabilitationRepository;
use App\Repository\PersonneRepository;
use App\Repository\TgAdrMailRepository;
use App\Repository\TgBlocRepository;
use App\Repository\TgDocumentRepository;
use App\Repository\TgResumeRepository;
use App\Repository\TlBlocFormRepository;
use App\Repository\TrGenreRepository;
use App\Repository\TrLangueRepository;
use App\Repository\TrTypeDocRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Repository\TgParametreRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Form\Blocs\Type\BlAnnexePrepropositionType;
use App\Repository\AppelProjetRepository;
use App\Repository\TtGestionFormulairePhaseRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class FormulaireService
 * @package App\Service
 */
class FormulaireService
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var TlBlocFormRepository
     */
    private $blocFormRepository;

    /**
     * @var TgBlocRepository
     */
    private $tgBlocRepository;

    /**
     * @var TgParametreRepository
     */
    private $tgParametreRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var TrGenreRepository
     */
    private $trGenreRepository;

    /**
     * @var TgResumeRepository
     */
    private $tgResumeRepository;

    /**
     * @var AppelProjetRepository
     */
    private $appelProjetRepository;

    /**
     * @var TtGestionFormulairePhaseRepository
     */
    private $ttGestionFormulairePhaseRepository;

    /**
     * @var HabilitationRepository
     */
    private $habilitationRepository;

    /**
     * @var TgAdrMailRepository
     */
    private $adrMailRepository;

    /**
     * @var PersonneRepository
     */
    private $personneRepository;

    /**
     * @var TrLangueRepository
     */
    private $trLangueRepository;

    /**
     * @var TrTypeDocRepository
     */
    private $trTypeDocument;

    /**
     * @var TgDocumentRepository
     */
    private $documentRepository;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var OrganismeService
     */
    private $organismeService;

    /**
     * FormulaireService constructor.
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param EntityManagerInterface $registry
     * @param TlBlocFormRepository $blocFormRepository
     * @param TgBlocRepository $tgBlocRepository
     * @param TgParametreRepository $tgParametreRepository
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     * @param ParameterBagInterface $params
     * @param TrGenreRepository $trGenreRepository
     * @param TgResumeRepository $tgResumeRepository
     * @param AppelProjetRepository $appelProjetRepository
     * @param TtGestionFormulairePhaseRepository $ttGestionFormulairePhaseRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TrLangueRepository $trLangueRepository
     * @param UserManagerInterface $userManager
     * @param \Swift_Mailer $mailer
     * @param \Twig\Environment $templating
     * @param HabilitationRepository $habilitationRepository
     * @param TgAdrMailRepository $adrMailRepository
     * @param PersonneRepository $personneRepository
     * @param TgDocumentRepository $documentRepository
     * @param TrTypeDocRepository $trTypeDocument
     * @param EntityManagerInterface $entityManager
     * @param OrganismeService $organismeService
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        SessionInterface $session,
        EntityManagerInterface $registry,
        TlBlocFormRepository $blocFormRepository,
        TgBlocRepository $tgBlocRepository,
        TgParametreRepository $tgParametreRepository,
        TranslatorInterface $translator,
        ValidatorInterface $validator,
        ParameterBagInterface $params,
        TrGenreRepository $trGenreRepository,
        TgResumeRepository $tgResumeRepository,
        AppelProjetRepository $appelProjetRepository,
        TtGestionFormulairePhaseRepository $ttGestionFormulairePhaseRepository,
        AuthorizationCheckerInterface $authorizationChecker,
        TrLangueRepository $trLangueRepository,
        UserManagerInterface $userManager,
        \Swift_Mailer $mailer,
        \Twig\Environment $templating,
        HabilitationRepository $habilitationRepository,
        TgAdrMailRepository $adrMailRepository,
        PersonneRepository $personneRepository,
        TgDocumentRepository $documentRepository,
        TrTypeDocRepository $trTypeDocument,
        EntityManagerInterface $entityManager,
        OrganismeService $organismeService

    ){
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
        $this->registry = $registry;
        $this->blocFormRepository = $blocFormRepository;
        $this->tgBlocRepository = $tgBlocRepository;
        $this->tgParametreRepository = $tgParametreRepository;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->params = $params;
        $this->trGenreRepository = $trGenreRepository;
        $this->tgResumeRepository = $tgResumeRepository;
        $this->appelProjetRepository = $appelProjetRepository;
        $this->ttGestionFormulairePhaseRepository = $ttGestionFormulairePhaseRepository;
        $this->authorizationChecker = $authorizationChecker;
        $this->trLangueRepository = $trLangueRepository;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->habilitationRepository = $habilitationRepository;
        $this->adrMailRepository = $adrMailRepository;
        $this->personneRepository = $personneRepository;
        $this->documentRepository = $documentRepository;
        $this->trTypeDocument = $trTypeDocument;
        $this->partenariatRepository = $entityManager->getRepository(TgPartenariat::class);
        $this->mcLibreRepository = $entityManager->getRepository(TgMcLibre::class);
        $this->idExternesRepository = $entityManager->getRepository(TgIdExternes::class);
        $this->tgOrganisme = $entityManager->getRepository(TgOrganisme::class);
        $this->coordProj = $entityManager->getRepository(TgCoordinationProj::class);
        $this->tgCompteBancaire = $entityManager->getRepository(TgCompteBancaire::class);
        $this->user = $entityManager->getRepository(User::class);
        $this->trProfil = $entityManager->getRepository(TrProfil::class);
        $this->organismeService = $organismeService;
    }

    /**
     * @param int $idFormulaire
     * @return array
     */
    public function GetListBlocs(int $idFormulaire): array
    {
        $listBlocs = array();
        $formulaire = $this->blocFormRepository->findAllBlocsByFormulaireId($idFormulaire);

        foreach ($formulaire as $blocs) {
            $lib = $this->tgBlocRepository->findOneBy(['idBloc' => $blocs["id_bloc"]]);
            $listBlocs[$blocs["ordre"]] = ['id_bloc' => $blocs["id_bloc"], 'libelle' => $lib->getLbBloc(), 'form_type' => $lib->getClassName(), 'ordre' => $blocs["ordre"]];
        }

        return $listBlocs;
    }

    /**
     * @param array $listBlocks
     * @param int $idBloc
     * @return int
     */
    public function getNextBlockByRang(array $listBlocks, int $idBloc): int
    {
        $rang = 0;
        foreach ($listBlocks as $bloc){
            if($idBloc === (int)$bloc['id_bloc']) $rang = $bloc['ordre'];
        }

        $next = $rang + 1;
        return  (array_key_exists($next, $listBlocks)) ? (int)$listBlocks[$next]['id_bloc'] : (int)$listBlocks[1]['id_bloc'];
    }

    /**
     * @param array $listBlocks
     * @param int $idBloc
     * @return int
     */
    public function getPreviousBlockByRang(array $listBlocks, int $idBloc): int
    {
        $rang = 0;
        foreach ($listBlocks as $bloc){
            if($idBloc === (int)$bloc['id_bloc']) $rang = $bloc['ordre'];
        }

        $next = $rang - 1;
        return  (array_key_exists($next, $listBlocks)) ? (int)$listBlocks[$next]['id_bloc'] : (int)$listBlocks[1]['id_bloc'];

    }

    /**
     * @param TgBloc $tgBloc
     * @param TgProjet $tgProjet
     * @param $options
     * @param $listBlocs
     * @return \Symfony\Component\Form\FormInterface
     */
    public function GetFormType(TgBloc $tgBloc, TgProjet $tgProjet, $options, $listBlocs)
    {
        $className = $tgBloc->getClassName()? : '';
        if ($tgProjet && 'BlInstFiType' !== $className && $tgProjet->getIdProjet() === null) {
            $options = array('op_disabled' => true);
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.no.project'));
        }

        switch ($className) {
            case 'BlInstFiType':
                $form = $this->createForm(BlInstFiType::class, $tgProjet);
                break;
            case 'BlIdentProjType':
                $form = $this->createForm(BlIdentProjType::class, $tgProjet, $options);
                break;
            case 'BlDocScientifiqueType':
                $form = $this->createForm(BlDocScientifiqueType::class, $tgProjet, $options);
                break;
            case 'BlAnnexePrepropositionType':
                $form = $this->createForm(BlAnnexePrepropositionType::class, $tgProjet, $options);
                break;
            case 'BlInfoComplType':
                $form = $this->createForm(BlInfoComplType::class, $tgProjet, $options);
                break;
            case 'BlResumeType':
                $form = $this->createForm(BlResumeType::class, $tgProjet, $options);
                break;
            case 'BlMotCleCesType':
                $form = $this->createForm(BlMotCleCesType::class, $tgProjet, $options);
                break;
            case 'BlMotCleErcType':
                $form = $this->createForm(BlMotCleErcType::class, $tgProjet, $options);
                break;
            case 'BlMotCleLibreType':
                $form = $this->createForm(BlMotCleLibreType::class, $tgProjet, $options);
                break;
            case 'NonSouhaiteType':
                $form = $this->createForm(NonSouhaiteType::class, $tgProjet, $options);
                break;
            case 'BlPartenariatType':
                $form = $this->createForm(BlPartenariatType::class, $tgProjet, $options);
                break;
            case 'BlSoumettreType':
                $form = $this->createForm(BlSoumettreType::class, $tgProjet, $options);
                break;
            case 'BlSyntheseType':
                $form = $this->createForm(BlSyntheseType::class, $tgProjet, $options);
                break;
            default:
                $form = $this->createForm(BlInstFiType::class, $tgProjet, $options);
                break;
        }

        $lastBloc = end($listBlocs);
        if($lastBloc['id_bloc'] == $tgBloc->getIdBloc()) {
            $form->add('saveBloc', SubmitType::class, ['label' => 'Enregistrer', 'attr' => array('class' => 'btn btn btn-warning pull-right btn')]);
			$form->add('saveBlocWithoutRedirect', SubmitType::class, ['label' => 'Enregister', 'attr' => array('class' => 'btn btn btn-warning pull-right')]);
            $form->add('saveFormulaire', SubmitType::class, ['label' => 'Soumettre ma Pré-proposition', 'attr' => array('class' => 'btn btn-primary')]);
	   } else {
            $form->add('saveBloc', SubmitType::class, ['label' => 'Suivant >>', 'attr' => array('class' => 'btn btn-primary pull-right')]);
            $form->add('saveBlocWithoutRedirect', SubmitType::class, ['label' => 'Enregister', 'attr' => array('class' => 'btn btn btn-warning pull-right')]);
            $form->add('saveFormulaire', SubmitType::class, ['label' => 'Soumettre ma Pré-proposition', 'attr' => array('class' => 'btn btn-primary')]);

        }

        return $form;

    }

    /**
     * @param int|null $idAppel
     * @param int $idPhase
     * @param int|null $idFormulaire
     * @param string $field
     * @param array $options
     * @return int
     */
    public function manageFormPhaseVisibility(int $idAppel=null, int $idPhase, int $idFormulaire=null, string $field, array $options)
    {
        $havePermission = false;
        $ttGestionFormPhase = $this->ttGestionFormulairePhaseRepository->findBy(['idPhase' => $idPhase, 'field' => $field]);

        if($ttGestionFormPhase) {
            foreach ($ttGestionFormPhase as $roles) {
                $role = $roles->getRole();
                if($this->authorizationChecker->isGranted($role)) $havePermission = true;
            }

            $permissions = $this->ttGestionFormulairePhaseRepository->findPermissions($idAppel=null, $idPhase, $idFormulaire=null, $field);
            if(!$permissions->getVisibility()) {
                return 1;
            }
        }
        return 0;
    }

    /**
     * @param int|null $idAppel
     * @param int $idPhase
     * @param int|null $idFormulaire
     * @param string $field
     * @param array $options
     * @return bool
     */
    public function manageFormPhasePermissions(int $idAppel=null, int $idPhase, int $idFormulaire=null, string $field, array $options)
    {
        $havePermission = false;
        $ttGestionFormPhase = $this->ttGestionFormulairePhaseRepository->findBy(['idPhase' => $idPhase, 'field' => $field]);

        if($ttGestionFormPhase) {
            foreach ($ttGestionFormPhase as $roles) {
                $role = $roles->getRole();
                if($this->authorizationChecker->isGranted($role)) $havePermission = true;
            }

            $permissions = $this->ttGestionFormulairePhaseRepository->findPermissions($idAppel=null, $idPhase, $idFormulaire=null, $field);
            if("L" == $permissions->getPermissions()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @param $idInfraFi
     * @param $idUser
     * @param $tgPhase
     * @return bool
     */
    public function processingBlInstFiType(TgProjet $tgProjet, int $idAppel, Form $form, $idInfraFi, $idUser, $tgPhase)
    {
        $em = $this->registry;
        $tgAppel = $this->appelProjetRepository->findOneby(['idAppel' => $idAppel])?:  new TgAppelProj();

        try {
            $tgProjet->setBlDemLabel(false);
            $tgProjet->setBlDemCofi(false);
            $tgProjet->setBlInfraRecherche(false);
            if(!$tgProjet->getIdAppel())  $tgProjet->setIdAppel($tgAppel);
            if(!$tgProjet->getPorteur())$tgProjet->setPorteur($idUser);

            $em->persist($tgProjet);

            $tgHabProj = $this->habilitationRepository->findOneby(['idPersonne' => $idUser, 'idProfil' => 15])?: '';
            if ($tgHabProj) $tgHabProj->addIdProjet($tgProjet);

            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
            return true;

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlIdentProjType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlSyntheseType(TgProjet $tgProjet, int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
            return false;
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @param $idInfraFi
     * @param $idUser
     * @param $idPhase
     * @return bool
     */
    public function processingBlDocScientifiqueType(TgProjet $tgProjet,  int $idAppel, Form $form, $idInfraFi, $idUser, $idPhase)
    {
        if ($tgProjet->getIdInfraFi() !== null) {

            $file = $form['lbPreproposition']->getData();
            $uploadFile = ''; $newFilename = '';
            if($file){
                //  $filesystem = new Filesystem();
                //   $filesystem->chmod($file->getLinkTarget(), 0777);

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //  $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();
                $tgProjet->setFile($file);

                $typeFile=''; $tailleFile =''; $nbrMaxPagesFile='';
                $paramsFile = $this->tgParametreRepository->findParamsDocSc( $idAppel, 'FMT_DOC_SC', 'TAILLE_FIC_MAX', 'NB_PAGES_MAX');
                if ($paramsFile) {
                    foreach ($paramsFile as $paramFile) {
                        $typeFile = ($paramFile->getLbCode() === 'FMT_DOC_SC')? $paramFile->getLbValeur() : 'pdf';
                        $tailleFile =  ($paramFile->getLbCode() === 'TAILLE_FIC_MAX')? $paramFile->getLbValeur() : '100';
                        $nbrMaxPagesFile = ($paramFile->getLbCode() === 'NB_PAGES_MAX')? $paramFile->getLbValeur() : '100';
                    }

                    $fileConstraints = $this->constraintsFile($typeFile, $tailleFile, $nbrMaxPagesFile);

                    $errorsByBloc = $this->validator->validate($file, $fileConstraints);

                    if (count($errorsByBloc) > 0) {
                        $this->getDocErrorMessage($errorsByBloc);
                        return false;
                    }

                    // $nbrPagesFile = $this->getNbrPages($file->getLinkTarget(), $file->guessExtension());
//                    if ($nbrMaxPagesFile < $nbrPagesFile) {
//                        $this->session->getFlashBag()->add('error', 'Le nombre maximum de pages par fichier ne doit pas dépasser ' . $nbrMaxPagesFile . ' pages.');
//                        return false;
//                    }
                }
                $uploadFile = $this->uploadedFile($file, $newFilename, 'files_directory_doc_sc');
            }

            try {
                $idLangue = ($form['lbLangue']->getData())? $form['lbLangue']->getData()->getIdLangue(): null;
                $trLangue = $this->trLangueRepository->findOneBy([ 'idLangue' => $idLangue]);
                $tgDocument = $this->documentRepository->findOneBy([ 'idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 1, 'idPhase' => $idPhase])?: new TgDocument();
                $trTypeDoc = $this->trTypeDocument->findOneBy([ 'idTypeDoc' => 1]);
                $tgDocument
                    ->setIdProjet($tgProjet)
                    ->setIdPhase($idPhase)
                    ->setIdLangue($trLangue)
                    ->setIdTypeDoc($trTypeDoc)
                ;

                if($uploadFile) {
                    $tgDocument->setLbNomFichier($newFilename);
                }

                $em = $this->registry;
                $em->persist($tgDocument);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
                return false;
            }

            return true;
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @param $idInfraFi
     * @param $idUser
     * @param $idPhase
     * @return bool
     */
    public function processingBlAnnexePrepropositionType(TgProjet $tgProjet,  int $idAppel, Form $form, $idInfraFi, $idUser, $idPhase)
    {
        if ($tgProjet->getIdInfraFi() !== null) {

            $file = $form['lbAnnexePreproposition']->getData();

            if($file) {

                // $filesystem = new Filesystem();
                // $filesystem->chmod($file->getLinkTarget(), 0777);

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //  $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();
                $tgProjet->setFile($file);

                $typeFile=''; $tailleFile =''; $nbrMaxPagesFile='';

                $paramsFile = $this->tgParametreRepository->findParamsDocSc( $idAppel, 'FMT_AN_PRE_PROPOSOTI', 'TAILLE_FIC_AN_MAX', 'NB_PAGES_AN_MAX');
                if ($paramsFile) {
                    foreach ($paramsFile as $paramFile) {
                        if ($paramFile->getLbCode() === 'FMT_AN_PRE_PROPOSOTI') $typeFile = $paramFile->getLbValeur();
                        if ($paramFile->getLbCode() === 'TAILLE_FIC_AN_MAX') $tailleFile = $paramFile->getLbValeur();
                        if ($paramFile->getLbCode() === 'NB_PAGES_AN_MAX') $nbrMaxPagesFile = $paramFile->getLbValeur();
                    }

                    $fileConstraints = $this->constraintsFile($typeFile, $tailleFile, $nbrMaxPagesFile);

                    $errorsByBloc = $this->validator->validate($file, $fileConstraints);

                    if (count($errorsByBloc) > 0) {
                        $this->getDocErrorMessage($errorsByBloc);
                        return false;
                    }

//                    $nbrPagesFile = $this->getNbrPages($file->getLinkTarget(), $file->guessExtension());
//                    if ($nbrMaxPagesFile < $nbrPagesFile) {
//                        $this->session->getFlashBag()->add('error', 'Le nombre maximum de pages par fichier ne doit pas dépasser ' . $nbrMaxPagesFile . ' pages.');
//                        return false;
//                    }
                }
                $uploadFile = $this->uploadedFile($file, $newFilename, 'files_directory_an_pre_proposition');
                if($uploadFile) {
                    try {
                        $tgDocument = $this->documentRepository->findOneBy([ 'idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 2, 'idPhase' => $idPhase])?: new TgDocument();
                        $trTypeDoc = $this->trTypeDocument->findOneBy([ 'idTypeDoc' => 2]);
                        $tgDocument
                            ->setIdProjet($tgProjet)
                            ->setIdPhase($idPhase)
                            ->setIdTypeDoc($trTypeDoc)
                            ->setLbNomFichier($newFilename);

                        $em = $this->registry;
                        $em->persist($tgDocument);
                        $em->flush();
                        $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                        return true;
                    } catch (\Exception $e) {
                        $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlInfoComplType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {

                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlResumeType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {

                $em = $this->registry;
                $trLangueFr = $this->trLangueRepository->findOneBy([ 'idLangue' => 1]);
                $trLangueEn = $this->trLangueRepository->findOneBy([ 'idLangue' => 2]);
                $tgResumeFR = ($this->tgResumeRepository->findOneBy(['idProjet' => $tgProjet->getIdProjet(), 'idLangue' => 1]))?: new TgResume();
                $tgResumeFR->setLbTexte($form->get('resumeFr')->getViewData());
                $tgResumeFR->setIdLangue($trLangueFr);
                $tgResumeFR->setIdProjet($tgProjet);

                $tgResumeEN = ($this->tgResumeRepository->findOneBy(['idProjet' => $tgProjet->getIdProjet(), 'idLangue' => 2]))?: new TgResume();
                $tgResumeEN->setLbTexte($form->get('resumeEn')->getViewData());
                $tgResumeEN->setIdLangue($trLangueEn);
                $tgResumeEN->setIdProjet($tgProjet);

                $em->persist($tgResumeFR);
                $em->persist($tgResumeEN);
                $em->flush();

                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlMotCleCesType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlMotCleErcType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlMotCleLibreType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingNonSouhaiteType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
                return true;
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param int $idAppel
     * @param Form $form
     * @return bool
     */
    public function processingBlPartenariatType(TgProjet $tgProjet,  int $idAppel, Form $form)
    {
        try {
            if ($tgProjet->getIdInfraFi() !== null) {
                $em = $this->registry;
                $em->persist($tgProjet);
                $em->flush();
                return true;
                $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param Request $request
     */
    public function nonSouhaiteAdd(TgProjet $tgProjet, Request $request)
    {
        try {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $courriel = $request->request->get('courriel');
            $organisme = $request->request->get('organisme');
            $lbMotif = $request->request->get('lbMotif');

            $tgNonSouhaite = new TgNonSouhaite();
            $tgNonSouhaite->setNom($nom);
            $tgNonSouhaite->setPrenom($prenom);
            $tgNonSouhaite->setCourriel($courriel);
            $tgNonSouhaite->setOrganisme($organisme);
            $tgNonSouhaite->setLbMotif($lbMotif);
            $tgNonSouhaite->addIdProjet($tgProjet);

            $em = $this->registry;
            $em->persist($tgNonSouhaite);
            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgNonSouhaite $tgNonSouhaite
     * @param string $nom
     * @param string $prenom
     * @param string $courriel
     * @param string $organisme
     * @param string $lbMotif
     */
    public function nonSouhaiteEdit(TgNonSouhaite $tgNonSouhaite, string $nom, string $prenom, string $courriel, string $organisme, string $lbMotif)
    {
        try {
            $tgNonSouhaite->setNom($nom);
            $tgNonSouhaite->setPrenom($prenom);
            $tgNonSouhaite->setCourriel($courriel);
            $tgNonSouhaite->setOrganisme($organisme);
            $tgNonSouhaite->setLbMotif($lbMotif);

            $em = $this->registry;
            $em->persist($tgNonSouhaite);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param TgNonSouhaite $tgNonSouhaite
     */
    public function nonSouhaiteDel(TgProjet $tgProjet, TgNonSouhaite $tgNonSouhaite)
    {
        try {
            $tgProjet->removeIdNonSouhaite($tgNonSouhaite);

            $em = $this->registry;
            $em->persist($tgProjet);
            $em->remove($tgNonSouhaite);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgMcLibre $tgMcLibre
     * @param string $terme_fr
     * @param string $terme_en
     */
    public function mcLibreEdit(TgMcLibre $tgMcLibre, string $terme_fr, string $terme_en)
    {
        try {
            $tgMcLibre->setLbNom($terme_fr);
            $tgMcLibre->setLbNomEn($terme_en);

            $em = $this->registry;
            $em->persist($tgMcLibre);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgMcLibre $tgMcLibre
     */
    public function mcLibreDel(TgMcLibre $tgMcLibre)
    {
        try {
            $em = $this->registry;
            $em->remove($tgMcLibre);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenaire
     * @param TrCivilite $trCiv
     * @param string $nom
     * @param string $prenom
     * @param string $mail
     * @param string $orcid
     */
    public function participantAdd(TgPartenariat $tgPartenaire, $trCiv=null, $nom, $prenom, $mail, $orcid, $resp, $coord, $typOrcid, $tgUtilisateur, $url, $profil)
    {
        try {
            $em = $this->registry;

            /////// Add Participant //////////////////////////////////////////////
            $tgAdrMail =  $this->adrMailRepository->findOneby(['adrMail' => $mail])?: null;
            $tgPersonne = ($tgAdrMail)? $this->personneRepository->findOneby(['idPersonne' => $tgAdrMail->getIdPersonne()]) : null;
            if($tgPersonne) {

                if($trCiv) $tgPersonne->setIdCivilite($trCiv);

                if($orcid) {
                    $tgIdExternes = $this->addRefExterne($tgPersonne, $typOrcid, $orcid);
                }

                $em->persist($tgPersonne);

            } else {
                $tgPersonne = $this->addPersonne($nom, $prenom, null, $trCiv, null);

                if($orcid) {
                    $tgIdExternes = $this->addRefExterne($tgPersonne, $typOrcid, $orcid);
                }

                if ($mail) {
                    $tgAdrMailga = new TgAdrMail();
                    $tgAdrMailga
                        ->setAdrMail($mail)
                        ->setIdPersonne($tgPersonne)
                        ->setAdrPref(true);
                    $em->persist($tgAdrMailga);
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////
            if($resp){
                //// enlever le role pour l'ancien resp sc
                $tgHabit =  ($tgPartenaire->getRespScient())? $this->habilitationRepository->findOneby(['idPersonne' => $tgPartenaire->getRespScient()->getIdPersonne(), 'idProfil' => 12]):null;

                if($tgHabit) {
                    $em->remove($tgHabit);
                }

                $tgPersonneOld = $tgPartenaire->getRespScient();
                if($tgPersonneOld) $tgPartenaire->removeIdPersonne($tgPersonneOld);

                $em->persist($tgPartenaire);

                $tgPartenaire->setRespScient($tgPersonne);
                $em->persist($tgPartenaire);

                if($coord){
                    $coord->setIdPersonne($tgPersonne);
                    $em->persist($coord);
                }
                if ($mail) {
                    //// find personne in bdd with mail , saved if not exist and send register mail
                    $this->sendMailRegistration($tgUtilisateur, $url, $profil, $tgPersonne, $mail, "SC");
                }
            } else {
                $this->sendMailNotif($url, $mail);
            }

            $tgPartenaire->addIdPersonne($tgPersonne);

            $em->persist($tgPartenaire);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPersonne $tgParticipant
     * @param Request $request
     * @param TrCivilite $trCiv
     * @param TgPartenariat $tgPartenaire
     * @param $coord
     * @param $typOrcid
     * @param $tgUtilisateur
     * @param $url
     * @param $profil
     */
    public function participantEdit(TgPersonne $tgParticipant, Request $request, TrCivilite $trCiv=null, TgPartenariat $tgPartenaire, $coord, $typOrcid, $tgUtilisateur, $url, $profil)
    {
        try {
            $resp = $request->request->get('resp');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $orcid = $request->request->get('orcid');
            $mailParticipant = $request->request->get('mail');

            $em = $this->registry;
            /////// Add Participant //////////////////////////////////////////////
            $tgAdrMail =  $this->adrMailRepository->findOneby(['adrMail' => $mailParticipant])?: null;
            $tgPersonne = ($tgAdrMail)? $this->personneRepository->findOneby(['idPersonne' => $tgAdrMail->getIdPersonne()]) : null;

            if ($tgPersonne) {
                if ($trCiv) $tgPersonne->setIdCivilite($trCiv);
                if ($orcid) {
                    $tgIdExternes = $this->addRefExterne($tgPersonne, $typOrcid, $orcid);
                }

                $em->persist($tgPersonne);

            }
            else {
                $tgPersonne = $this->addPersonne($nom, $prenom, null, $trCiv, null);

                if($orcid) {
                    $tgIdExternes = $this->addRefExterne($tgPersonne, $typOrcid, $orcid);
                }

                if ($mailParticipant) {
                    $tgAdrMailga = new TgAdrMail();
                    $tgAdrMailga
                        ->setAdrMail($mailParticipant)
                        ->setIdPersonne($tgPersonne);
                    $em->persist($tgAdrMailga);
                }

            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////
            if($resp){
                //// enlever le role pour l'ancien resp sc
                $oldRespSc =  ($tgPartenaire->getRespScient())? $tgPartenaire->getRespScient()->getIdPersonne():null;
                $tgHabit = ($oldRespSc)?$this->habilitationRepository->findOneby(['idPersonne' => $oldRespSc, 'idProfil' => 12]):null;
                if($tgHabit) {
                    $em->remove($tgHabit);
                }

                $tgPersonneOld = $tgPartenaire->getRespScient();
                if($tgPersonneOld) $tgPartenaire->removeIdPersonne($tgPersonneOld);

                $em->persist($tgPartenaire);

                $tgPartenaire->setRespScient($tgPersonne);
                $em->persist($tgPartenaire);

                if($coord){
                    $coord->setIdPersonne($tgPersonne);
                    $em->persist($coord);
                }

                if ($mailParticipant) {
                    //// find personne in bdd with mail , saved if not exist and send register mail
                    $this->sendMailRegistration($tgUtilisateur, $url, $profil, $tgPersonne, $mailParticipant, "SC");
                }
            } else {
                $this->sendMailNotif($url, $mailParticipant);
            }

            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgPersonne $tgPersonne
     * @param TgPartenariat $tgPartenaire
     * @param TgAdrMail $tgAdrMail
     */
    public function participantDel(TgPersonne $tgPersonne, TgPartenariat $tgPartenaire, TgAdrMail $tgAdrMail)
    {
        try {
            $tgPartenaire->removeIdPersonne($tgPersonne);
            if($tgPartenaire->getRespScient() == $tgPersonne) $tgPartenaire->setRespScient(null);
            $em = $this->registry;
            $em->persist($tgPartenaire);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
    }

    /**
     * @param TgProjet $tgProjet
     * @param TgOrganisme $tgOrganisme
     * @param TrTypePart $typPart
     * @param $trPays
     * @param $coord
     * @return TgPartenariat
     */
    public function etrAdd(TgProjet $tgProjet, TgOrganisme $tgOrganisme, TrTypePart $typPart, $trPays, $coord)
    {
        try {
            $em = $this->registry;

            $em->persist($tgOrganisme);

            $tgPartenariat = new TgPartenariat();
            $tgPartenariat
                ->setIdProjet($tgProjet)
                ->setTypPart($typPart)
                ->setLaboratoire($tgOrganisme)
            ;

            if(!$coord) $this->CoordinateursEnreg($tgProjet, null, $tgOrganisme, $trPays, $coord);

            $partenaire = $tgPartenariat;

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
            return $partenaire;
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenariat
     * @param Request $request
     * @param $trPays
     */
    public function etrEdit(TgPartenariat $tgPartenariat, Request $request, $trPays)
    {
        try {

            $name = $request->request->get('name');
            $laboratoire = $request->request->get('laboratoire');
            $city = $request->request->get('city');

            $em = $this->registry;

            $tgPartenariat->getLaboratoire()->setLbLaboratoire($laboratoire);
            $tgPartenariat->getLaboratoire()->setLbNomFr($name);
            $tgPartenariat->getLaboratoire()->setVille($city);

            if($trPays) $tgPartenariat->getLaboratoire()->setCdPays($trPays);

            $em->persist($tgPartenariat);

            $tgOrganisme = $this->tgOrganisme->findOneBy(['idOrganisme' => $tgPartenariat->getLaboratoire()])?: null;

            $coordcheck = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'idOrganisme' => $tgPartenariat->getLaboratoire()])?: null;

            if($coordcheck) {
                $em->remove($coordcheck);
                $em->flush();
            }

            $coord = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'cdPays' => $trPays->getCdPays()]) ?: null;
            if(!$coord) $this->CoordinateursEnreg($tgPartenariat->getIdProjet(), null, $tgOrganisme, $trPays, $coord);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param Request $request
     * @param TrTypePart $typPart
     * @param $trPays
     * @param $coord
     * @return TgPartenariat
     */
    public function pufAdd(TgProjet $tgProjet, Request $request, TrTypePart $typPart, $trPays, $coord, $tutLib)
    {
        try {
            $rnsr = $request->request->get('rnsr');
            $siret = $request->request->get('siret');
            $name_tut_heb = $request->request->get('name_tut_heb');
            $delegation = $request->request->get('delegation');
            $laboratoire = $request->request->get('laboratoire');
            $code_unite = $request->request->get('code_unite');
            $adress_tut_heb = $request->request->get('adress_tut_heb');
            $compl_adress_tut_heb = $request->request->get('compl_adress_tut_heb');
            $postal_code_tut_heb = substr($request->request->get('postal_code_tut_heb'), 0,5);
            $city_tut_heb = $request->request->get('city_tut_heb');
            // $country_tut_heb = $request->request->get('country_tut_heb')?: 'france';

            $firstname_direct_lab = $request->request->get('firstname_direct_lab');
            $lastname_direct_lab = $request->request->get('lastname_direct_lab');
            $courriel_direct_lab = $request->request->get('courriel_direct_lab');

            $siret_tut_gest = $request->request->get('siret_tut_gest');
            $name_tut_gest = $request->request->get('name_tut_gest');
            $sigle = $request->request->get('sigle');
            $adress_tut_gest = $request->request->get('adress_tut_gest');
            $compl_adress_tut_gest = $request->request->get('compl_adress_tut_gest');
            $postal_code_tut_gest = substr($request->request->get('postal_code_tut_gest'), 0,5);
            $city_tut_gest = $request->request->get('city_tut_gest');
            $country_tut_gest = $request->request->get('country_tut_gest');
            $banque_tut_g_puf = $request->request->get('banque_tut_g_puf');
            $rib_tut_g_puf = $request->request->get('rib_tut_g_puf');
            $iban_tut_g_puf = $request->request->get('iban_tut_g_puf');

            $firstname_rep_juridique =  $request->request->get('firstname_rep_juridique');
            $lastname_rep_juridique =  $request->request->get('lastname_rep_juridique');
            $function_rep_juridique =  $request->request->get('function_rep_juridique');

            //  $gender_gest_admin =  $request->request->get('gender_gest_admin');
            $lastname_gest_admin =  $request->request->get('lastname_gest_admin');
            $firstname_gest_admin =  $request->request->get('firstname_gest_admin');
            $mail_gest_admin =  $request->request->get('mail_gest_admin');

            $em = $this->registry;

            /////// Add Gest Admin //////////////////////////////////////////////
            $gestAdmin = null;
            if ($mail_gest_admin) {
                $tgUtilisateur = $this->user->findOneby(['emailCanonical' => strtolower($mail_gest_admin)]);
                $profil = $this->trProfil->findOneby(['idProfil' => 12]);
                $url = $request->headers->get('host');
                $gestAdmin = $this->AddPersonneWithMail($mail_gest_admin, $lastname_gest_admin, $firstname_gest_admin, $tgUtilisateur, $url, $profil, "AD");
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            /////// Add Resp Jur //////////////////////////////////////////////
            $respJur = null;
            if ($lastname_rep_juridique) $respJur = $this->addPersonne($lastname_rep_juridique, $firstname_rep_juridique, $function_rep_juridique);

            /////// Add Tutelle Gest //////////////////////////////////////////////
            // Creation de l'organisme, et ajout de l'RNSR SIRET
            $tgOrganismeTutGest = null;
            if($siret_tut_gest) {
                $tgOrganismeTutGest = $this->organismeService->verifAndAddOrganisme(
                    $rnsr = null,
                    $siret_tut_gest,
                    $name_tut_gest,
                    $laboratoire = null,
                    $code_unite = null,
                    $sigle,
                    $adress_tut_gest,
                    $compl_adress_tut_gest,
                    $postal_code_tut_gest,
                    $city_tut_gest,
                    $trPays
                );
            }

            /////// Add Dir Lab //////////////////////////////////////////////
            $directLab = null;
            if ($courriel_direct_lab) {
                $directLab = $this->AddPersonneWithMail($courriel_direct_lab, $lastname_direct_lab, $firstname_direct_lab, null, null, null, "DR");
            }

            /////// Add Tutelle Heb //////////////////////////////////////////////
            // Creation de l'organisme, et ajout de l'RNSR SIRET
            $tgOrganismeTutHeb = $this->organismeService->verifAndAddOrganisme(
                $rnsr,
                $siret,
                $tutLib,
                $laboratoire,
                $code_unite,
                $sigle = null,
                $adress_tut_heb,
                $compl_adress_tut_heb,
                $postal_code_tut_heb,
                $city_tut_heb,
                $trPays
            );

            /////// Add Partenaire //////////////////////////////////////////////
            $tgPartenariat = $this->addPartenaire($tgProjet, $typPart, $tgOrganismeTutHeb, $tgOrganismeTutGest, $respJur, $delegation, $directLab, $gestAdmin);

            if($iban_tut_g_puf) {
                $tgCompteBancaire = $this->addComptesBq($banque_tut_g_puf, $iban_tut_g_puf, $rib_tut_g_puf);
                $tgPartenariat->setIdCompte($tgCompteBancaire->getIdCompte());
            }

            if(!$coord) $this->CoordinateursEnreg($tgProjet, null, $tgOrganismeTutHeb, $trPays, $coord);

            $partenaire = $tgPartenariat;

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

            return $partenaire;

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenariat
     * @param $tgAdrMailDirectLab
     * @param $tgAdrMailGestAdmin
     * @param $trPays
     * @param Request $request
     */
    public function pufEdit(TgPartenariat $tgPartenariat, $tgAdrMailDirectLab, $tgAdrMailGestAdmin, $trPays, Request $request)
    {
        try {
            $rnsr = $request->request->get('rnsr');
            $siret = $request->request->get('siret');
            $name_tut_heb = $request->request->get('name_tut_heb');
            $delegation = $request->request->get('delegation');
            $laboratoire = $request->request->get('laboratoire');
            $code_unite = $request->request->get('code_unite');
            $adress_tut_heb = $request->request->get('adress_tut_heb');
            $compl_adress_tut_heb = $request->request->get('compl_adress_tut_heb');
            $postal_code_tut_heb = $request->request->get('postal_code_tut_heb');
            $city_tut_heb = $request->request->get('city_tut_heb');
            $country_tut_heb = $request->request->get('country_tut_heb');

            $firstname_direct_lab = $request->request->get('firstname_direct_lab');
            $lastname_direct_lab = $request->request->get('lastname_direct_lab');
            $courriel_direct_lab = $request->request->get('courriel_direct_lab');


            $siret_tut_gest = $request->request->get('siret_tut_gest');
            $name_tut_gest = $request->request->get('name_tut_gest');
            $sigle = $request->request->get('sigle');
            $adress_tut_gest = $request->request->get('adress_tut_gest');
            $compl_adress_tut_gest = $request->request->get('compl_adress_tut_gest');
            $postal_code_tut_gest = substr($request->request->get('postal_code_tut_gest'), 0,5);
            $city_tut_gest = $request->request->get('city_tut_gest');
            $country_tut_gest = $request->request->get('country_tut_gest');
            $banque_tut_g_puf = $request->request->get('banque_tut_g_puf');
            $rib_tut_g_puf = $request->request->get('rib_tut_g_puf');
            $iban_tut_g_puf = $request->request->get('iban_tut_g_puf');


            //  $gender_gest_admin = $request->request->get('gender_gest_admin');
            $lastname_gest_admin = $request->request->get('lastname_gest_admin');
            $firstname_gest_admin = $request->request->get('firstname_gest_admin');
            $mail_gest_admin = $request->request->get('mail_gest_admin');

            $firstname_rep_juridique =  $request->request->get('firstname_rep_juridique');
            $lastname_rep_juridique =  $request->request->get('lastname_rep_juridique');
            $function_rep_juridique =  $request->request->get('function_rep_juridique');

            $em = $this->registry;

            /////// Add Tutelle Gest //////////////////////////////////////////////
            // Creation de l'organisme, et ajout de l'RNSR SIRET
            $tgOrganismeTutGest = null;
            if($tgOrganismeTutGest) {
                $tgOrganismeTutGest = $this->organismeService->verifAndAddOrganisme(
                    $rnsr = null,
                    $siret_tut_gest,
                    $name_tut_gest,
                    $laboratoire = null,
                    $code_unite = null,
                    $sigle,
                    $adress_tut_gest,
                    $compl_adress_tut_gest,
                    $postal_code_tut_gest,
                    $city_tut_gest,
                    $trPays
                );
            }

            /////// Edit Resp Jur //////////////////////////////////////////////
            if($tgPartenariat->getRepJuridique()) {
                $tgPartenariat->getRepJuridique()
                    ->setLbNomUsage($lastname_rep_juridique)
                    ->setLbPrenom($firstname_rep_juridique)
                    ->setFonction($function_rep_juridique)
                ;
            }

            ///////////////// Edit Gest Adm //////////////////////////////////////////////
            $gestAdmin = null;
            if ($mail_gest_admin) {
                    //// enlever le role pour l'ancien resp sc
                    $oldGestAd = ($tgPartenariat->getGestAdm()) ? $tgPartenariat->getGestAdm()->getIdPersonne() : null;
                    $tgOldUtilisateur = ($oldGestAd) ? $this->user->findOneby(['idPersonne' => $oldGestAd]) : null;
                    $tgUtilisateur = $this->user->findOneby(['emailCanonical' => strtolower($mail_gest_admin)]);

                    if (!$tgUtilisateur && $tgOldUtilisateur) {
                        $em->remove($tgOldUtilisateur);
                        $em->flush();
                    }

                $profil = $this->trProfil->findOneby(['idProfil' => 12]);
                $url = $request->headers->get('host');
                $gestAdmin = $this->AddPersonneWithMail($mail_gest_admin, $lastname_gest_admin, $firstname_gest_admin, $tgUtilisateur, $url, $profil, "AD");
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////// Edit Dir Lab //////////////////////////////////////////////
            $directLab = null;
            if ($courriel_direct_lab) {
                $directLab = $this->AddPersonneWithMail($courriel_direct_lab, $lastname_direct_lab, $firstname_direct_lab, null, null, null, "DR");
            }

            // Creation de l'organisme, et ajout de l'RNSR SIRET
            $tgOrganismeTutHeb = $this->organismeService->verifAndAddOrganisme(
                $rnsr,
                $siret,
                $name_tut_heb,
                $laboratoire,
                $code_unite,
                $sigle = null,
                $adress_tut_heb,
                $compl_adress_tut_heb,
                $postal_code_tut_heb,
                $city_tut_heb,
                $trPays
            );

            if('<span class="select2-selection__placeholder">Choisir une délégation</span>' == $delegation) $delegation= null;

            if($gestAdmin) $tgPartenariat->setGestAdm($gestAdmin);
            if($directLab) $tgPartenariat->setDirLabo($directLab);
            $tgPartenariat
                ->setLbDeleguation($delegation)
                ->setHebergeur($tgOrganismeTutHeb)
                ->setTutGest($tgOrganismeTutGest)
            ;

            if($iban_tut_g_puf) {
                $tgCompteBancaire = $this->addComptesBq($banque_tut_g_puf, $iban_tut_g_puf, $rib_tut_g_puf);
                $tgPartenariat->setIdCompte($tgCompteBancaire->getIdCompte());
            }

            $coordcheck = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'idOrganisme' => $tgPartenariat->getHebergeur()])?: null;

            if($coordcheck) {
                $em->remove($coordcheck);
                $em->flush();
            }

            $coord = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'cdPays' => $trPays->getCdPays()]) ?: null;

            if(!$coord) $this->CoordinateursEnreg($tgPartenariat->getIdProjet(), null, $tgOrganismeTutHeb, $trPays, $coord);

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param Request $request
     * @param TrTypePart $typPart
     * @param $trPays
     * @param $coord
     * @return TgPartenariat
     */
    public function prfAdd(TgProjet $tgProjet, Request $request, TrTypePart $typPart, $trPays, $coord)
    {
        try {

            $siret_tut_gest = $request->request->get('siret_tut_gest');
            $name_tut_gest = $request->request->get('name_tut_gest');
            $sigle = $request->request->get('sigle');
            $adress_tut_gest = $request->request->get('adress_tut_gest');
            $compl_adress_tut_gest = $request->request->get('compl_adress_tut_gest');
            $postal_code_tut_gest = substr($request->request->get('postal_code_tut_gest'), 0,5);
            $city_tut_gest = $request->request->get('city_tut_gest');
            $country_tut_gest = $request->request->get('country_tut_gest');
            $banque_tut_g_prf = $request->request->get('banque_tut_g_prf');
            $rib_tut_g_prf = $request->request->get('rib_tut_g_prf');
            $iban_tut_g_prf = $request->request->get('iban_tut_g_prf');

            $firstname_rep_juridique =  $request->request->get('firstname_rep_juridique');
            $lastname_rep_juridique =  $request->request->get('lastname_rep_juridique');
            $function_rep_juridique =  $request->request->get('function_rep_juridique');

            // $gender_gest_admin =  $request->request->get('gender_gest_admin');
            $lastname_gest_admin =  $request->request->get('lastname_gest_admin');
            $firstname_gest_admin =  $request->request->get('firstname_gest_admin');
            $mail_gest_admin =  $request->request->get('mail_gest_admin');

            $em = $this->registry;

            ///////////////// Edit Gest Adm //////////////////////////////////////////////
            $gestAdmin = null;
            if ($mail_gest_admin) {
                $tgUtilisateur = $this->user->findOneby(['emailCanonical' => strtolower($mail_gest_admin)]);
                $profil = $this->trProfil->findOneby(['idProfil' => 12]);
                $url = $request->headers->get('host');
                $gestAdmin = $this->AddPersonneWithMail($mail_gest_admin, $lastname_gest_admin, $firstname_gest_admin, $tgUtilisateur, $url, $profil, "AD");
            }
            ///////// Add Reso Jur //////////////////////////////////
            $respJur = null;
            if ($lastname_rep_juridique) $respJur = $this->addPersonne($lastname_rep_juridique, $firstname_rep_juridique, $function_rep_juridique);

            ///////////////////////// Edit organisme ////////////////////////////////////////////////
            $tgOrganismeTutGest = $this->organismeService->verifAndAddOrganisme(
                $rnsr = null,
                $siret_tut_gest,
                $name_tut_gest,
                $laboratoire = null,
                $code_unite = null,
                $sigle,
                $adress_tut_gest,
                $compl_adress_tut_gest,
                $postal_code_tut_gest,
                $city_tut_gest,
                $trPays
            );

            $tgPartenariat = $this->addPartenaire($tgProjet, $typPart, null, $tgOrganismeTutGest, $respJur, null, null, $gestAdmin);

            if($iban_tut_g_prf) {
                $tgCompteBancaire = $this->addComptesBq($banque_tut_g_prf, $iban_tut_g_prf, $rib_tut_g_prf);
                $tgPartenariat->setIdCompte($tgCompteBancaire->getIdCompte());
            }

            if(!$coord) $this->CoordinateursEnreg($tgProjet, null, $tgOrganismeTutGest, $trPays, $coord);

            $partenaire = $tgPartenariat;

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

            return $partenaire;

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenariat
     * @param Request $request
     * @param $tgAdrMailGestAdmin
     * @param $trPays
     * @return TgPartenariat
     */
    public function prfEdit(TgPartenariat $tgPartenariat, Request $request, $tgAdrMailGestAdmin, $trPays)
    {
        try {
            $siret_tut_gest = $request->request->get('siret_tut_gest');
            $name_tut_gest = $request->request->get('name_tut_gest');
            $sigle = $request->request->get('sigle');
            $adress_tut_gest = $request->request->get('adress_tut_gest');
            $compl_adress_tut_gest = $request->request->get('compl_adress_tut_gest');
            $postal_code_tut_gest = substr($request->request->get('postal_code_tut_gest'), 0,5);
            $city_tut_gest = $request->request->get('city_tut_gest');
            $country_tut_gest = $request->request->get('country_tut_gest');

            $banque_tut_g_prf = $request->request->get('banque_tut_g_prf');
            $rib_tut_g_prf = $request->request->get('rib_tut_g_prf');
            $iban_tut_g_prf = $request->request->get('iban_tut_g_prf');


            $lastname_gest_admin =  $request->request->get('lastname_gest_admin');
            $firstname_gest_admin =  $request->request->get('firstname_gest_admin');
            $mail_gest_admin =  $request->request->get('mail_gest_admin');

            $firstname_rep_juridique =  $request->request->get('firstname_rep_juridique');
            $lastname_rep_juridique =  $request->request->get('lastname_rep_juridique');
            $function_rep_juridique =  $request->request->get('function_rep_juridique');

            $em = $this->registry;

            ///////////////// Edit Gest Adm //////////////////////////////////////////////
            $gestAdmin = null;
            if ($mail_gest_admin) {
                //// enlever le role pour l'ancien resp sc
                $oldGestAd = ($tgPartenariat->getGestAdm()) ? $tgPartenariat->getGestAdm()->getIdPersonne() : null;
                $tgOldUtilisateur = ($oldGestAd) ? $this->user->findOneby(['idPersonne' => $oldGestAd]) : null;

                $tgUtilisateur = $this->user->findOneby(['emailCanonical' => strtolower($mail_gest_admin)]);
                if (!$tgUtilisateur && $tgOldUtilisateur) {
                    $em->remove($tgOldUtilisateur);
                    $em->flush();
                }

                $profil = $this->trProfil->findOneby(['idProfil' => 12]);
                $url = $request->headers->get('host');
                $gestAdmin = $this->AddPersonneWithMail($mail_gest_admin, $lastname_gest_admin, $firstname_gest_admin, $tgUtilisateur, $url, $profil, "AD");

                $mail = ($tgAdrMailGestAdmin->getAdrMail())?: '';
                if($mail !== $mail_gest_admin){
                    if($mail) $tgPartenariat->getGestAdm()->removeIdAdrMail($tgAdrMailGestAdmin);
                }

                //// find personne in bdd with mail , saved if not exist and send register mail
                $this->sendMailRegistration($tgUtilisateur, $url, $profil, $tgPartenariat->getGestAdm(), $mail_gest_admin,"AD");
            }

            if($gestAdmin) $tgPartenariat->setGestAdm($gestAdmin);
            /////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////// Add Reso Jur //////////////////////////////////
            if($tgPartenariat->getRepJuridique()) {
                $tgPartenariat->getRepJuridique()
                    ->setLbNomUsage($lastname_rep_juridique)
                    ->setLbPrenom($firstname_rep_juridique)
                    ->setFonction($function_rep_juridique)
                ;
            }

            if(!$tgPartenariat->getGestAdm()){
                $tgPartenariat->setGestAdm(new TgPersonne());
            }

            $tgOrganismeTutGest = $this->organismeService->verifAndAddOrganisme(
                $rnsr = null,
                $siret_tut_gest,
                $name_tut_gest,
                $laboratoire = null,
                $code_unite = null,
                $sigle,
                $adress_tut_gest,
                $compl_adress_tut_gest,
                $postal_code_tut_gest,
                $city_tut_gest,
                $trPays
            );

            $tgPartenariat->setTutGest($tgOrganismeTutGest);

            if($iban_tut_g_prf) {
                $tgCompteBancaire = $this->addComptesBq($banque_tut_g_prf, $iban_tut_g_prf, $rib_tut_g_prf);
                $tgPartenariat->setIdCompte($tgCompteBancaire->getIdCompte());
            }

            $coordcheck = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'idOrganisme' => $tgPartenariat->getTutGest()])?: null;

            if($coordcheck) {
                $em->remove($coordcheck);
                $em->flush();
            }

            $coord = $this->coordProj->findOneby(['idProjet' => $tgPartenariat->getIdProjet(), 'cdPays' => $trPays->getCdPays()]) ?: null;
            if(!$coord) $this->CoordinateursEnreg($tgPartenariat->getIdProjet(), null, $tgOrganismeTutGest, $trPays, $coord);

            $em->persist($tgPartenariat);

            $em->flush();

            $partenaire = $tgPartenariat;

            return $partenaire;

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenariat
     * @param TgProjet $tgProjet
     * @param Request $request
     * @param TrTypePart $typPart
     * @return TgPartenariat
     */
    public function coutPufAdd(TgPartenariat $tgPartenariat, TgProjet $tgProjet, Request $request, TrTypePart $typPart)
    {
        try {

            $ct_personnels_permanents =  $request->request->get('ct_personnels_permanents')?:0;
            $ct_personnels_non_permanents_ss_fin =  $request->request->get('ct_personnels_non_permanents_ss_fin')?:0;
            $ct_personnels_non_permanents =  $request->request->get('ct_personnels_non_permanents')?:0;
            $ct_decharge =  $request->request->get('ct_decharge')?:0;
            $ct_instruments =  $request->request->get('ct_instruments')?:0;
            $ct_batiments =  $request->request->get('ct_batiments')?:0;
            $ct_prestation_service =  $request->request->get('ct_prestation_service')?:0;
            $ct_frais_gen =  $request->request->get('ct_frais_gen')?:0;
            $pt_personnels_permanents =  $request->request->get('pt_personnels_permanents')?:0;
            $pt_personnels_non_permanents_ss_fin =  $request->request->get('pt_personnels_non_permanents_ss_fin')?:0;
            $pt_personnels_non_permanents =  $request->request->get('pt_personnels_non_permanents')?:0;
            $pt_decharge =  $request->request->get('pt_decharge')?:0;
            $taux_aide_dde = $request->request->get('taux_aide_dde')?:0;
            $mnt_aide_dde = $request->request->get('mnt_aide_dde')?:0;
            $taux_frais_pers  = $request->request->get('taux_frais_pers')?:0;
            $autres_dep  = $request->request->get('autres_dep')?:0;
            $taux_frais_env  = $request->request->get('taux_frais_env')?:0;

            $em = $this->registry;

            $tgCoutPrev = new TgCoutPrev();
            $tgCoutPrev
                ->setMntPersPerm($ct_personnels_permanents)
                ->setMntPersPermMois($pt_personnels_permanents)
                ->setMntPersNpNf($ct_personnels_non_permanents_ss_fin)
                ->setMntPersNpNfMois($pt_personnels_non_permanents_ss_fin)
                ->setMntPersNp($ct_personnels_non_permanents)
                ->setMntPersNpMois($pt_personnels_non_permanents)
                ->setMntDechEns($ct_decharge)
                ->setMntDechEnsMois($pt_decharge)
                ->setMntInstMat($ct_instruments)
                ->setMntBatTer($ct_batiments)
                ->setMntPrest($ct_prestation_service)
                ->setMntFraisG($ct_frais_gen)
                ->setIdPartenaire($tgPartenariat)
            ;

            $em->persist($tgCoutPrev);

            $tgPartenariat
                ->setIdCoutPrv($tgCoutPrev)
                ->setTxAide($taux_aide_dde)
                ->setMntAide($mnt_aide_dde)
                ->setTxFraisEnv($taux_frais_env)
                ->setTxFraisPers($taux_frais_pers)
                ->setAutresDep($autres_dep)
            ;

            $partenaire = $tgPartenariat;

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

            return $partenaire;

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgPartenariat $tgPartenariat
     * @param TgCoutPrev $tgCoutPrev
     * @param Request $request
     * @return TgPartenariat
     */
    public function coutPrevEdit(TgPartenariat $tgPartenariat, TgCoutPrev $tgCoutPrev, Request $request)
    {
        try {

            $ct_personnels_permanents =  $request->request->get('ct_personnels_permanents')?:0;
            $ct_personnels_non_permanents_ss_fin =  $request->request->get('ct_personnels_non_permanents_ss_fin')?:0;
            $ct_personnels_non_permanents =  $request->request->get('ct_personnels_non_permanents')?:0;
            $ct_decharge =  $request->request->get('ct_decharge')?:0;
            $ct_instruments =  $request->request->get('ct_instruments')?:0;
            $ct_batiments =  $request->request->get('ct_batiments')?:0;
            $ct_prestation_service =  $request->request->get('ct_prestation_service')?:0;
            $ct_frais_gen =  $request->request->get('ct_frais_gen')?:0;
            $pt_personnels_permanents =  $request->request->get('pt_personnels_permanents')?:0;
            $pt_personnels_non_permanents_ss_fin =  $request->request->get('pt_personnels_non_permanents_ss_fin')?:0;
            $pt_personnels_non_permanents =  $request->request->get('pt_personnels_non_permanents')?:0;
            $pt_decharge =  $request->request->get('pt_decharge')?:0;
            $taux_aide_dde = $request->request->get('taux_aide_dde')?:0;
            $mnt_aide_dde = $request->request->get('mnt_aide_dde')?:0;
            $taux_frais_pers  = $request->request->get('taux_frais_pers')?:0;
            $autres_dep  = $request->request->get('autres_dep')?:0;
            $taux_frais_env  = $request->request->get('taux_frais_env')?:0;

            $em = $this->registry;

            $tgCoutPrev
                ->setMntPersPerm($ct_personnels_permanents)
                ->setMntPersPermMois($pt_personnels_permanents)
                ->setMntPersNpNf($ct_personnels_non_permanents_ss_fin)
                ->setMntPersNpNfMois($pt_personnels_non_permanents_ss_fin)
                ->setMntPersNp($ct_personnels_non_permanents)
                ->setMntPersNpMois($pt_personnels_non_permanents)
                ->setMntDechEns($ct_decharge)
                ->setMntDechEnsMois($pt_decharge)
                ->setMntInstMat($ct_instruments)
                ->setMntBatTer($ct_batiments)
                ->setMntPrest($ct_prestation_service)
                ->setMntFraisG($ct_frais_gen)
                ->setIdPartenaire($tgPartenariat)
            ;

            $em->persist($tgCoutPrev);

            $tgPartenariat
                ->setTxAide($taux_aide_dde)
                ->setMntAide($mnt_aide_dde)
                ->setTxFraisEnv($taux_frais_env)
                ->setTxFraisPers($taux_frais_pers)
                ->setAutresDep($autres_dep)
            ;


            $partenaire = $tgPartenariat;

            $em->persist($tgPartenariat);

            $em->flush();

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));

            return $partenaire;

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgProjet $tgProjet
     * @param string $lbNomFr
     * @param string $lbNomEn
     */
    public function TgMcLibre(TgProjet $tgProjet, string $lbNomFr, string $lbNomEn)
    {
        try {
            $tgMcLibre = new TgMcLibre();
            $tgMcLibre->setIdProjet($tgProjet);
            $tgMcLibre->setLbNom($lbNomFr);
            $tgMcLibre->setLbNomEn($lbNomEn);

            $em = $this->registry;
            $em->persist($tgMcLibre);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @param TgPersonne $tgPersonne
     * @param TrTypIdExt $typOrcid
     * @param string $orcid
     * @return TgIdExternes|null
     */
    public function addRefExterne(TgPersonne $tgPersonne, TrTypIdExt $typOrcid, string $orcid) {
        $tgIdExternes = $this->idExternesRepository->findOneby(['idPersonne' => $tgPersonne->getIdPersonne()])?: null;
        if(!$tgIdExternes) {
            $em = $this->registry;
            $tgIdExternes = new  TgIdExternes();
            $tgIdExternes
                ->setIdPersonne($tgPersonne)
                ->setIdTypeRefExt($typOrcid)
                ->setNumIdentifiant($orcid);
            $em->persist($tgIdExternes);
            $em->flush();
        }
        return $tgIdExternes;
    }

    /**
     * @param string|null $lastname
     * @param string|null $firstname
     * @param string|null $function
     * @param TrCivilite|null $civilite
     * @param TrGenre|null $gender
     * @return TgPersonne
     */
    public function addPersonne(string $lastname =null, string $firstname = null, string $function= null, TrCivilite $civilite = null, TrGenre $gender = null) {
        $em = $this->registry;

        $personne = new TgPersonne();
        if($lastname) $personne->setLbNomUsage($lastname);
        if($firstname)$personne->setLbPrenom($firstname);
        if($function)$personne->setFonction($function);
        if($civilite)$personne->setIdCivilite($civilite);
        if($gender)$personne->setIdGenre($gender);

        $em->persist($personne);
        $em->flush();

        return $personne;
    }

    /**
     * @param string $mail
     * @param string|null $lastname
     * @param string|null $firstname
     * @param null $tgUtilisateur
     * @param null $url
     * @param null $profil
     * @param string $typ
     * @return TgPersonne|void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addPersonneWithMail(string $mail, string $lastname = null, string $firstname = null, $tgUtilisateur = null, $url = null, $profil = null, string $typ) {
        $tgAdrMail =  $this->adrMailRepository->findOneby(['adrMail' => $mail])?: null;

        if($tgAdrMail)
            $personne = $this->personneRepository->findOneby(['idPersonne' => $tgAdrMail->getIdPersonne()])?: new TgPersonne();
        else {
            $personne = $this->addPersonne($lastname, $firstname, null);

            $em = $this->registry;
            $tgAdrMailga = new TgAdrMail();
            $tgAdrMailga
                ->setAdrMail($mail)
                ->setIdPersonne($personne)
                ->setAdrPref(true);
            $em->persist($tgAdrMailga);

            $em->flush();
        }
        //// find personne in bdd with mail , saved if not exist and send register mail
        if ($profil) $this->sendMailRegistration($tgUtilisateur, $url, $profil, $personne, $mail, $typ);

        return $personne;
    }

    /**
     * @param string $banque
     * @param string $iban
     * @param string $rib
     * @return TgCompteBancaire
     */
    public function addComptesBq(string $banque, string $iban, string $rib) {

        $tgCompteBancaire = $this->tgCompteBancaire->findOneby(['iban' => $this->encrypt($iban)]) ?: null;
        if (!$tgCompteBancaire) {
            $tgCompteBancaire = new TgCompteBancaire();
            $tgCompteBancaire->setBanque($banque);
            $tgCompteBancaire->setIban($this->encrypt($iban));
            $tgCompteBancaire->setRib($this->encrypt($rib));

            $this->registry->persist($tgCompteBancaire);
            $this->registry->flush();
        }

        return $tgCompteBancaire;
    }

    /**
     * @param TgProjet|null $tgProjet
     * @param TrTypePart|null $typPart
     * @param TgOrganisme|null $tgOrganismeTutHeb
     * @param TgOrganisme|null $tgOrganismeTutGest
     * @param TgPersonne|null $respJur
     * @param string|null $delegation
     * @param TgPersonne|null $directLab
     * @param TgPersonne|null $gestAdmin
     * @return TgPartenariat
     */
    public function addPartenaire(
        TgProjet $tgProjet = null,
        TrTypePart $typPart = null,
        TgOrganisme $tgOrganismeTutHeb = null,
        TgOrganisme $tgOrganismeTutGest = null,
        TgPersonne $respJur = null,
        string $delegation = null,
        TgPersonne $directLab = null,
        TgPersonne $gestAdmin = null
    )
    {
        $tgPartenariat = new TgPartenariat();
        $tgPartenariat
            ->setIdProjet($tgProjet)
            ->setTypPart($typPart)
            ->setHebergeur($tgOrganismeTutHeb)
            ->setTutGest($tgOrganismeTutGest)
            ->setRepJuridique($respJur)
            ->setLbDeleguation($delegation);

        if ($directLab) $tgPartenariat->setDirLabo($directLab);
        if ($gestAdmin) $tgPartenariat->setGestAdm($gestAdmin);

        $this->registry->persist($tgPartenariat);
        $this->registry->flush();

        return $tgPartenariat;
    }

    /**
     * @param TgPartenariat $tgPartenaire
     * @param TgCoutPrev $tgCp
     */
    public function partenaireDel(TgPartenariat $tgPartenaire, TgCoutPrev $tgCp)
    {
        try {
            $em = $this->registry;
            if($tgCp) {
                $em->remove($tgCp);
                $em->flush();
            }
            $em->remove($tgPartenaire);
            $em->flush();
            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param TgResume $tgResume
     * @param string $resumeFr
     * @param string $resumeEn
     */
    public function resumeEnreg(TgResume $tgResume, string $resumeFr, string $resumeEn, TgProjet $tgProjet, TrLangue $trlangueFr, TrLangue $trlangueEn)
    {
        try {

            if($tgResume->getIdProjet()) {
                $this->tgResumeRepository->setResume($resumeFr, $tgProjet->getIdProjet(), $trlangueFr->getIdLangue());
                $this->tgResumeRepository->setResume($resumeEn, $tgProjet->getIdProjet(), $trlangueEn->getIdLangue());
            }
            else {
                $tgResumeFr = new TgResume();
                $tgResumeFr->setLbTexte($resumeFr);
                $tgResumeFr->setIdLangue($trlangueFr);
                $tgResumeFr->setIdProjet($tgProjet);

                $tgResumeEn = new TgResume();
                $tgResumeEn->setLbTexte($resumeEn);
                $tgResumeEn->setIdLangue($trlangueEn);
                $tgResumeEn->setIdProjet($tgProjet);

                $em = $this->registry;
                $em->persist($tgResumeFr);
                $em->persist($tgResumeEn);
                $em->flush();
            }

            $this->session->getFlashBag()->add('success', $this->translator->trans('bloc.success.save.project'));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param $tgProjet
     * @param $RespSc
     * @param $tgOrganisme
     * @param $trPays
     * @param $coord
     */
    public function CoordinateursEnreg($tgProjet, $RespSc, $tgOrganisme, $trPays, $coord)
    {
        try {
            $tgCoord = new TgCoordinationProj();
            $tgCoord->setIdProjet($tgProjet);
            $tgCoord->setIdOrganisme($tgOrganisme);
            $tgCoord->setIdPersonne($RespSc);
            $tgCoord->setCdPays($trPays);

            $em = $this->registry;
            $em->persist($tgCoord);

            if($coord) $em->remove($coord);

            $em->flush();

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }

    }

    /**
     * @param null $typeFile
     * @param null $tailleFile
     * @param null $nbrMaxPagesFile
     * @return array
     */
    private function ConstraintsFile($typeFile=null, $tailleFile=null, $nbrMaxPagesFile=null)
    {
        $typeConstraint = '';
        // mime types
        if (strtolower($typeFile) === 'pdf') $typeConstraint = 'application/pdf';
        if (strtolower($typeFile) === 'xls') $typeConstraint = 'application/vnd.ms-excel';
        if (strtolower($typeFile) === 'xlsx') $typeConstraint = 'application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        if (strtolower($typeFile) === 'zip') $typeConstraint = 'application/zip';
        if (strtolower($typeFile) === 'ppt') $typeConstraint = 'application/vnd.ms-powerpoint';
        if (strtolower($typeFile) === 'pptx') $typeConstraint = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        if (strtolower($typeFile) === 'csv') $typeConstraint = 'text/csv';
        if (strtolower($typeFile) === 'doc') $typeConstraint = 'application/msword';
        if (strtolower($typeFile) === 'docx') $typeConstraint = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

        // images
        if (strtolower($typeFile) === 'png' ||  strtolower($typeFile) === 'jpg' || strtolower($typeFile) === 'jpeg' || strtolower($typeFile) === 'gif')
            $typeConstraint = 'image/'.strtolower($typeFile);

        $constraints = [ 'maxSize' => '1024M'];
        if($typeConstraint && $tailleFile) $constraints = [ 'mimeTypes' => $typeConstraint, 'maxSize' => $tailleFile . 'M'];

        $fileConstraints = [
            new NotBlank(),
            new File(
                $constraints
            )
        ];

        return $fileConstraints;
    }

    /**
     * @param $file
     * @param $newFilename
     * @param $rep
     * @return bool
     */
    private function uploadedFile($file, $newFilename, $rep)
    {
        if ($file instanceof UploadedFile) {
            // upload file
            try {
                $file->move(
                // $parameterValue = $this->params->get('parameter_name');
                    $this->params->get($rep),
                    $newFilename
                );
                return true;
            } catch (FileException $e) {
                $this->session->getFlashBag()->add('error', "erreur lors de l'upload du fichier");
            }
        }
        return false;
    }

    /**
     * @param $file
     * @param $type
     * @return int|mixed
     */
    private function getNbrPages($file, $type)
    {
        if (file_exists($file)) {
            //open the file for reading
            if ($type === 'pdf') {
                if ($handle = @fopen($file, "rb")) {
                    $count = 0;
                    $i = 0;
                    $contents = '';
                    while (!feof($handle)) {
                        if ($i > 0) {
                            $contents .= fread($handle, 8152);
                        } else {
                            $contents = fread($handle, 1000);
                            //In some pdf files, there is an N tag containing the number of
                            //of pages. This doesn't seem to be a result of the PDF version.
                            //Saves reading the whole file.
                            if (preg_match("/\/N\s+([0-9]+)/", $contents, $found)) {
                                return $found[1];
                            }
                        }
                        $i++;
                    }
                    fclose($handle);

                    //get all the trees with 'pages' and 'count'. the biggest number
                    //is the total number of pages, if we couldn't find the /N switch above.
                    if (preg_match_all("/\/Type\s*\/Pages\s*.*\s*\/Count\s+([0-9]+)/", $contents, $capture, PREG_SET_ORDER)) {
                        foreach ($capture as $c) {
                            if ($c[1] > $count)
                                $count = $c[1];
                        }
                        return $count;
                    }
                }
            }

            if($type === 'doc' || $type === 'txt'){
                $num = count(file($file));
                return $num;
            }
        }
        return 0;
    }

    /**
     * @param $tgUtilisateur
     * @param $url
     * @param $profil
     * @param $personne
     * @param $mail
     * @param $typ
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function sendMailRegistration($tgUtilisateur, $url, $profil, $personne, $mail, $typ) {

        $em = $this->registry;

        $tgHabiltation = $this->habilitationRepository->findOneBy(['idPersonne' => $personne, 'idProfil' => $profil->getIdProfil()])?: new TgHabilitation();

        if(!$tgUtilisateur) {

            $subject = '[IRIS] Insciption - '. $profil->getLbProfil();
            $template = "registration_resp.html.twig";

            $user = $this->userManager->createUser();
            $user->setEnabled(false);
            $user->setConfirmationToken($this->generateToken());
            // $user->addRole('ROLE_RE_SCI');
            $user->setIdPersonne($personne);
            $user->setUsername($mail);
            $user->setEmail($mail);
            $user->setEmailCanonical(strtolower($mail));
            $user->setSalt(sha1(substr(md5((string) time()), 0, 10)));
            $user->setPassword($personne->getLbNomUsage());
            $user->setUsernameCanonical(strtolower($mail));
            $this->userManager->updateUser($user);
            $em->persist($user);

            $tgHabiltation->setIdPersonne($personne)
                ->setIdProfil($profil)
                ->setBlSupprime(1)
                ->setDhMaj(new \DateTime())
                ->setLbRespMaj('--------ddd-----------' . ' ' . $personne->getLbPrenom());

            $em->persist($tgHabiltation);


            $confirmation_email = 'http://' . $url . '/fr/register/user-register-sc/' . $user->getConfirmationToken();
            $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
                ->setTo([$mail])
                ->setBody(
                    $this->templating->render(
                        'emails/'.$template, ['confirmation_email' => $confirmation_email, 'profil' => $profil->getLbProfil()]),
                    'text/html');

            // Send the message
            $this->mailer->send($message);

        }else {
            $tgHabiltation->setIdPersonne($personne)
                ->setIdProfil($profil)
                ->setBlSupprime(1)
                ->setDhMaj(new \DateTime())
                ->setLbRespMaj('--------ddd-----------' . ' ' . $personne->getLbPrenom());
            $em->persist($tgHabiltation);

            ////// send notification ///////////////////////
            $subject = '[IRIS] Nouveau Rôle affecté - '. $profil->getLbProfil();
            $template = "notification_resp.html.twig";
            $link = 'http://' . $url . '/fr/login';
            $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
                ->setTo([$mail])
                ->setBody(
                    $this->templating->render(
                        'emails/' . $template, ['profil' => $profil->getLbProfil(), 'url' => $link]),
                    'text/html');

            // Send the message
            $this->mailer->send($message);
        }
        $em->flush();
    }

    /**
     * @param $url
     * @param $mail
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailNotif($url, $mail) {
        ////// send notification ///////////////////////
        $subject = '[ANR] Nouveau participant';
        $template = "notification_participant.html.twig";
        $link = 'http://' . $url . '/fr/register/user-register/';
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
            ->setTo([$mail])
            ->setBody(
                $this->templating->render(
                    'emails/' . $template, ['url' => $link]),
                'text/html');

        // Send the message
        $this->mailer->send($message);
    }

    /**
     * @param $mail
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailSoumission($mail) {
        ////// send notification ///////////////////////
        $subject = '[IRIS] Soumission du projet';
        $template = "soumission_projet.html.twig";
        // $link = 'http://' . $url . '/fr/register/user-register/';
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
            ->setTo([$mail])
            ->setBody(
                $this->templating->render(
                    'emails/' . $template),
                'text/html');

        // Send the message
        $this->mailer->send($message);
    }

    /**
     * @param $string
     * @return bool|false|string
     */
    private function encrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'fe67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';
        $secret_iv = 'fdd3345455fffgffffhkkyoife67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    /**
     * @param $string
     * @return bool|false|string
     */
    public function decrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'fe67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';
        $secret_iv = 'fdd3345455fffgffffhkkyoife67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    /**
     * @param $errorsByBloc
     */
    private function getDocErrorMessage($errorsByBloc) {

        $message=''; $typeAutorized='';
        foreach ($errorsByBloc as $key=> $violation) {

            foreach ($violation->getParameters() as  $keys=>$params) {
                //  if("{{ type }}" === $keys) $typeFile = $params;
                if("{{ types }}" === $keys) $typeAutorized = $params;
            }
            switch ($typeAutorized) {
                case '"application/pdf"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".pdf".';
                    break;
                case '"application/vnd.ms-excel"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".xls".';
                    break;
                case '"application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".xlsx".';
                    break;
                case '"application/zip"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".zip".';
                    break;
                case '"application/vnd.ms-powerpoint"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".ppt".';
                    break;
                case '"application/vnd.openxmlformats-officedocument.presentationml.presentation"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".pptx".';
                    break;
                case '"text/csv"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".csv".';
                    break;
                case '"application/msword"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".doc".';
                    break;
                case '"application/vnd.openxmlformats-officedocument.wordprocessingml.document"':
                    $message = 'Le type du fichier est invalide. Les types autorisés sont ".docx".';
                    break;
                default :  $message = $violation->getMessage();
            }
        }
        $this->session->getFlashBag()->add('error', $message);
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

                $violations = [
                    'field' => $violation->getPropertyPath(),
                    'message'      => $message,
                ];
            }
            return $violations;
        }
    }

    /**
     * @param $Bloc
     * @param $tgProjet
     * @param $form
     * @return array|ConstraintViolationListInterface
     */
    public function validateSoumission($Bloc, $tgProjet, $form) {
        $errors = array();
        if('BlInstFiType' === $Bloc['form_type']) {

            $instr = strtoupper($tgProjet->getIdInfraFi()->getLbNom());
            $pos = strpos($instr, 'PRCI');

            if ($pos) {
                $errors = $this->validator->validate($tgProjet, null, 'bloc_BlInstFiType_agence');
                $errors = $this->constraintViolationValidation($errors);
                if (!$errors) $errors = array();
            }
        }
        if('BlIdentProjType' === $Bloc['form_type']) {
            $min = ($tgProjet->getIdInfraFi())? $tgProjet->getIdInfraFi()->getMntMin() : null;
            $max = ($tgProjet->getIdInfraFi())? $tgProjet->getIdInfraFi()->getMntMax() : null;
            $montant = $tgProjet->getMntAidePrev()? : null;
            if ($montant && $max & $min) {
                if ($min > $montant || $max < $montant) {
                    $errors = [
                        'field' => 'mntAidePrev',
                        'message'      => 'Le montant provisionnel d\'aide n\'est pas renseigné ou n\'est pas correcte ',
                    ];
                }
            }
        }
        if('BlMotCleLibreType' === $Bloc['form_type']) {
            $mcLibre = $this->mcLibreRepository->findOneby(['idProjet' => $tgProjet->getIdProjet()]);
            if(!$mcLibre) {
                $errors = [
                    'field' => 'BlMotCleLibreType',
                    'message'      => 'Au moins un mot clé libre doit être renseigné ',
                ];
            }
        }
        if('NonSouhaiteType' === $Bloc['form_type']) {
            if($tgProjet->getIdNonSouhaite()->count() == 0) {
                $errors = [
                    'field' => 'NonSouhaiteType',
                    'message'      => 'Au moins un expert non souhaité doit être renseigné ',
                ];
            }
        }
        if('BlMotCleCesType' === $Bloc['form_type']) {
            if(0 === $tgProjet->getIdMcCes()->count()) {
                $errors = $this->validator->validate($tgProjet, null, 'BlMotCleCesType_ces');
                $errors = $this->constraintViolationValidation($errors);
            }
        }
        if('BlMotCleErcType' === $Bloc['form_type']) {
            if(0 === $tgProjet->getIdMcErc()->count()) {
                $errors = $this->validator->validate($tgProjet, null, 'BlMotCleErcType_erc');
                $errors = $this->constraintViolationValidation($errors);
            }
        }
        if('BlAnnexePrepropositionType' === $Bloc['form_type']) {
            $docSc = $this->documentRepository->findOneby(['idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 2]);
            if(!$docSc) {
                $errors = [
                    'field' => 'lbAnnexePreproposition',
                    'message'      => 'Le document annexe à la pré-proposition doit être renseigné ',
                ];
            }
        }

        return $errors;
    }

    /**
     * @param $tgProjet
     * @return array
     */
    public function validDoc($tgProjet) {
        $errors = array();
        $docSc = $this->documentRepository->findOneby(['idProjet' => $tgProjet->getIdProjet(), 'idTypeDoc' => 1]);
        if(!$docSc) {
            $errors = [
                'field' => 'lbPreproposition',
                'message'      => 'Le document scientifique doit être renseigné ',
            ];
        }
        if($docSc && ($docSc->getIdLangue() == null)) {
            $errors = [
                'field' => 'lbLangue',
                'message'      => 'La langue du document scientifique doit être renseignée ',
            ];
        }
        if($docSc && $docSc->getLbNomFichier() == null) {
            $errors = [
                'field' => 'lbPreproposition',
                'message'      => 'Le document scientifique doit être renseigné ',
            ];
        }
        return $errors;
    }

    /**
     * @param $field
     * @param $message
     * @return array
     */
    public function getErrorsValidation($field, $message)
    {
        $error = [
            'field' => $field,
            'message' => $message,
        ];

        return $error;
    }
}

