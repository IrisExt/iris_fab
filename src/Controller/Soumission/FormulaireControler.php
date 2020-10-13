<?php


namespace App\Controller\Soumission;


use App\Controller\BaseController;
use App\Entity\TgAppelProj;
use App\Entity\TgBloc;
use App\Entity\TgFormulaire;
use App\Entity\TgHabilitation;
use App\Entity\TgPhase;
use App\Entity\TgProjet;
use App\Entity\TlBlocForm;
use App\Entity\TlFormulaireAppel;
use App\Entity\TrClasseFormulaire;
use App\Form\FormulaireType;
use App\Repository\TgFormulaireRepository;
use App\Repository\TlBlocFormRepository;
use Doctrine\DBAL\DBALException;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TgBlocRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class FormulaireControler
 * @package App\Controller\Sommission
 * @Route("/form")
 */
class FormulaireControler extends BaseController
{
    /**
     * @var TlBlocFormRepository
     */
    private $tlBlocFormRepository;

    private $tgBlocRepository;

    private $tgFormulaireRepository;

    public function __construct(TlBlocFormRepository $tlBlocFormRepository, TgBlocRepository $tgBlocRepository, TgFormulaireRepository $tgFormulaireRepository)
    {
        $this->tlBlocFormRepository = $tlBlocFormRepository;
        $this->tgBlocRepository = $tgBlocRepository;
        $this->tgFormulaireRepository = $tgFormulaireRepository;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/index", name="index_soumission")
     */
    public function index()
    {
        $formulaire = $this->getEm()->getRepository(TgFormulaire::class)->findAll();
        // search pilote in habilitation
        $profil = $this->emRep(TgHabilitation::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idProfil' => 1])?:null;
        $profilPilote = $profil ? $profil->getIdProfil()->getIdProfil() : null;

       if($this->isGranted('ROLE_DOS_EM') || $profilPilote == 1){}else{throw new AccessDeniedException();}

        return $this->render('paramform/index.html.twig',
            [
                'formulaires' => $formulaire
            ]);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/newform", name="new_form")
     */
    public function newForm(Request $request): Response
    {

        $formulaire = new TgFormulaire();
        $appel = $this->getEmAppPro()->findAll();
        $form = $this->createForm(FormulaireType::class, $formulaire);
        $form->handleRequest($request);
        $blocs = $this->getDoctrine()->getRepository(TgBloc::class)->findby([], ['idBloc' => 'ASC']);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($formulaire);
                $data = explode(',', $request->request->get('DataFrom'));
                foreach ($data as $key => $blocnum) {
                    $entityBloc = $this->getDoctrine()->getRepository(TgBloc::class)->find($blocnum);
                    $BlocForm = new TlBlocForm();
                    $BlocForm->setIdFormulaire($formulaire)
                        ->setIdBloc($entityBloc)
                        ->setOrdre($key + 1)
                        ->setBlocParent($entityBloc->getIdBloc());
                    $em->persist($BlocForm);
                };
                $this->addFlash('success', 'Le formulaire ' . $formulaire->getLbFormulaire() . ' a bien été crée. ');
                $em->flush();
                return $this->redirectToRoute('index_soumission');

            } catch (DBALException $e) {
                $this->addFlash('error', 'Impossible de créer le formulaire');
                return $this->redirectToRoute('new_form');
            }
        }

        return $this->render('paramform/newform.html.twig',
            [
                'appels' => $appel,
                'blocs' => $blocs,
                'form' => $form->createView(),
            ]);
    }


    /**
     * @param TgFormulaire $formulaire
     * @return Response
     * afficher la page modification du formulaire
     * @Route("/modif/formulaire/{idFormulaire}" , name="update-formulaire")
     */
    public function updateFormulaire(TgFormulaire $formulaire, Request $request)
   {

//       if(!$this->isGranted('ROLE_DOS_EM')){
//           if(!$this->accesForm($formulaire)){
//               $this->addFlash('infos','Le formulaire est utilisé Impossible de le modifier ! ');
//               return $this->redirectToRoute('index_soumission');
//           };
//       }
        // après submit(modifier ordre formulaire
        if (!empty($request->request->get('ordre'))) {
            foreach ($request->request->get('ordre') as $value => $idbloc) {
                $bloc = $this->getEm()->getRepository(TlBlocForm::class)->findoneby(['idFormulaire' => $formulaire, 'idBloc' => $idbloc]);
                $bloc->setOrdre($value + 1);
                $this->getEm()->persist($bloc);
            }
            $this->addFlash('success', 'L\'ordre des blocs a bien été modifié');
            $this->getEm()->flush();
        };

        $blocs = $this->getDoctrine()->getRepository(TlBlocForm::class)->findBy(['idFormulaire' => $formulaire], ['ordre' => 'ASC']);

        return $this->render('paramform/modifierFormulaire.html.twig', [
            'modif_acces' => $this->accesForm($formulaire),
            'blocs' => $blocs,
            'formulaire' => $formulaire
        ]);
    }

    /**
     * Supprimer un formulaire
     * @param Request $request
     * @param TgFormulaire $idFormulaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/form/{idFormulaire}", name="delete_form")
     */
    public function deleteForm(Request $request, TgFormulaire $idFormulaire)
    {
      
if(!$this->accesForm($idFormulaire)){
    $this->addFlash('infos','Le formulaire est utilisé Impossible de supprimer, ');
    return $this->redirectToRoute('index_soumission');
};
  if ($this->isCsrfTokenValid('delete' . $idFormulaire->getIdFormulaire(), $request->request->get('_token'))) {
            $formWithAppel = $this->getEm()->getRepository(TlFormulaireAppel::class)->findBy(['idFormulaire' => $idFormulaire]);
            if (empty($formWithAppel)) {
                $formulaire = $this->getEm()->getRepository(TgFormulaire::class)->find($idFormulaire);
                $tlBlocForms = $this->getEm()->getRepository(TlBlocForm::class)->findBy(['idFormulaire' => $idFormulaire]);
                try {
                    foreach ($tlBlocForms as $tlBlocForm) {
                        $this->getEm()->remove($tlBlocForm);
                    }
                    $this->getEm()->remove($formulaire);
                    $this->getEm()->flush();
                } catch (DBALException $e) {
                    $this->addFlash('error', 'Une erreur s\'est prouite lors de la suppression ! Veuillez contacter l\'administrateur');
                    return $this->redirect($request->headers->get('referer'));
                }
                $this->addFlash('success', 'Le formulaire ' . $formulaire->getLbFormulaire() . ' à bien été supprimé');
            } else {
                $this->addFlash('error', 'Impossible de supprimer , Le formulaire est lié à un appel à projet');
            }
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param int $idFormulaire
     * @param Request $request
     * @return Response
     * @throws \Exception
     * Ajouter un bloc au formulaire
     * @Route("/update/bloc/form/{idFormulaire}", name="update_bloc_form")
     */
    public function updateBlocForm(int $idFormulaire, Request $request)
    {

        $formulaire = $this->getEm()->getRepository(TgFormulaire::class)->find($idFormulaire);
        $val = 100;
        if ($this->isCsrfTokenValid('addBlocForm' . $idFormulaire, $request->request->get('_token'))) {
            foreach ($request->request as $bloc => $key) {
                if ($bloc !== '_token') {
                    try {
                        $blocUpdate = $this->getEm()->getRepository(TgBloc::class)->find($key);
                        $tlbloc = new TlBlocForm();
                        $tlbloc->setIdFormulaire($formulaire)
                            ->setIdBloc($blocUpdate)
                            ->setOrdre($val);
                        $this->getEm()->persist($tlbloc);

                    } catch (DBALException $e) {
                        $this->addFlash('error', 'Une erreur s\'est prouite ! Veuillez contacter l\'administrateur');
                        return $this->redirect($request->headers->get('referer'));
                    }
                }
                $val++;
            };
            $this->getEm()->flush();
            // ordonner les blocs
            $ordreBlocs = $this->getEm()->getRepository(TlBlocForm::class)->findBy(['idFormulaire' => $formulaire], ['ordre' => 'ASC']);
            $ordre = 1; // ordonner les blocs dans le formulaire 1,2,3....;
            foreach ($ordreBlocs as $ordreBloc) {
                $ordreBloc->setOrdre($ordre);
                $this->getEm()->persist($ordreBloc);
                $ordre++;
            }
            $this->getEm()->flush();
            $this->addFlash('success', 'Le formulaire à bien été modifié.');
            return $this->redirect($request->headers->get('referer'));
        };

        if (!$formulaire) {
            throw new \Exception('Aucun formulaire trouvé', '404');
        }
        $blocs = $this->tgBlocRepository->findBlocNotwithForm($idFormulaire);
        if (empty($this->tlBlocFormRepository->findBlocWithFormulaire($formulaire))) {
            $blocs = $this->getEm()->getRepository(TgBloc::class)->findAll();
        }


        return $this->render('paramform/updateblocform.html.twig', [
            'countBloc' => count($blocs),
            'blocs' => $blocs,
            'formulaire' => $formulaire
        ]);
    }


    /**
     * supprimer un bloc du formulaire
     * @param TgBloc $bloc
     * @param TgFormulaire $formulaire
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/bloc/form/{idBloc}/{idFormulaire}" , name="delete_bloc_form")
     */
    public function deleteBlocForm(TgBloc $bloc, TgFormulaire $formulaire, Request $request)
    {

        if ($this->isCsrfTokenValid('delete' . $bloc->getIdBloc(), $request->request->get('_token'))) {
            $tlblocform = $this->getEm()->getRepository(TlBlocForm::class)->findOneBy(['idBloc' => $bloc, 'idFormulaire' => $formulaire]);

            $this->getEm()->remove($tlblocform);
            $this->getEm()->flush();
            $this->addFlash('success', 'Le bloc à bien été supprimé.');
            return $this->redirectToRoute('update-formulaire', ['idFormulaire' => $formulaire->getIdFormulaire()]);
        } else {
            $this->addFlash('error', 'Le bloc n\'a été supprimé !.');
        }
        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @param int $idAppel
     * @return Response
     * @Route("/affectation/{idAppel}", name="assignment_appel")
     */
    public function assignmentPhaseForForm(int $idAppel){

        $phases = $this->getEmPhase()->phaseForAppel($idAppel);

        return $this->render('paramform/choixphase.html.twig',[
            'phases' => $phases,
            'appel' => $idAppel
        ]);
    }
    /**
     * affectation du form. à appel
     * @Route("/affectation/{idAppel}/phase/{idPhase}", name="assignment_appel_phase")
     */
    public function assignmentForm(Request $request, int $idAppel, int $idPhase = null,  $action = '')
    {

        $appel = $this->getEmAppPro()->find($idAppel);

        if (!$appel) {
            throw new \Exception('Not found', '404');
        }
        if(!$request->request->get('phase') && $idPhase == null){
            return  $this->redirectToRoute('assignment_appel',['idAppel' => $idAppel ]);
        }

        $idphaseAppel = ($idPhase) ? $idPhase : $request->request->get('phase');
        $phases = $this->getEmPhase()->find($idphaseAppel);

        $forms = $this->getDoctrine()->getRepository(TlFormulaireAppel::class)->findBy(['idAppel' => $idAppel, 'idPhase' => $phases->getIdPhase()]);
        $forms = ($forms) ? $forms : '';
        $projetForm = $this->emRep(TgProjet::class)->findOneBy(['idAppel' => $appel]) ? true: false;

        $classform = $this->getDoctrine()->getRepository(TrClasseFormulaire::class)->findAll();


        return $this->render('paramform/affectformappel.html.twig', [
            'phases' => $phases,
            'action' => $action,
            'forms' => $forms,
            'classes' => $classform,
            'appel' => $appel,
            'projetForm' => $projetForm

        ]);
    }

    /**
     * modal affichage des formulaires
     * @Route("/formAppel/{idAppel}/{idClasseFormulaire}/{idPhase}/{updateForm}" , name="form-appel")
     */
    public function FormAppel(int $idAppel, int $idClasseFormulaire, TgPhase $idPhase, $updateForm = null)
    {

        $appel = $this->getEmAppPro()->find($idAppel);
        $classeFormulaire = $this->getDoctrine()->getRepository(TrClasseFormulaire::class)->find($idClasseFormulaire);
        $formulaire = $this->tgFormulaireRepository->updateAddFormulaire($classeFormulaire, $updateForm);

        return $this->render('paramform/addFormulaire.html.twig',
            [
                'phases' => $idPhase,
                'updateForm' => $updateForm,
                'formulaire' => $formulaire,
                'appel' => $appel,
                'classform' => $classeFormulaire
            ]);
    }


    /**
     * @param int $idAppel
     * @param int $idFormulaire
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/addForm/{idAppel}/{idFormulaire}/{idPhase}/{replace}" , name="add-form")
     * Ajouter ou modifier le formulaire de appel
     */
    public function addFormAppel(Request $request, TgAppelProj $idAppel, TgFormulaire $idFormulaire, TgPhase $idPhase, int $replace = null)
    {


        if (!$idFormulaire || !$idAppel || !$idPhase) {
            throw new \Exception('Le formulaire ou l\'appel sont introuvable', '404');
        }

        if ($replace !== null) {
            if ($this->isCsrfTokenValid('ajouterForm' . $idAppel->getIdAppel(), $request->request->get('_token'))) {
                $formulaireReplace = $this->getDoctrine()->getRepository(TgFormulaire::class)->find($idFormulaire);
                $tlformAppel = $this->getDoctrine()->getRepository(TlFormulaireAppel::class)->findOneBy(['idAppel' => $idAppel, 'idFormulaire' => $replace]);
                $tlformAppel->setIdAppel($idAppel);
                $tlformAppel->setIdFormulaire($idFormulaire);
                $tlformAppel->setIdPhase($idPhase);
                $this->getEm()->persist($tlformAppel);
                $this->addFlash('success', 'Le formulaire ' . $idFormulaire->getLbFormulaire() . ' vient d\'être remplacé ');
            } else {
                throw new Exception('formulaire inconnu', '404');
            }
        } else {
            $lierFormAppel = new TlFormulaireAppel();
            $lierFormAppel->setIdAppel($idAppel);
            $lierFormAppel->setIdFormulaire($idFormulaire);
            $lierFormAppel->setIdPhase($idPhase);
            $this->getEm()->persist($lierFormAppel);
            $this->addFlash('success', 'Le formulaire ' . $idFormulaire->getLbFormulaire() . ' vient d\'être ajouté ');
        }
        $this->getEm()->flush();


        return $this->redirectToRoute('assignment_appel_phase', ['idAppel' => $idAppel->getIdAppel(), 'idPhase' => $idPhase->getIdPhase()]);

    }

    /**
     * @param TgAppelProj $appel
     * @param TgFormulaire $formulaire
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/delete/{idAppel}/{idFormulaire}/{idPhase}" , name="delete-form")
     */
    public function deleteFormAppel(TgAppelProj $appel, TgFormulaire $formulaire, TgPhase $idPhase ,Request $request)
    {

        if ($this->isCsrfTokenValid('delete' . $formulaire->getIdFormulaire(), $request->request->get('_token'))) {
            $tlformAppel = $this->getDoctrine()->getRepository(TlFormulaireAppel::class)->findOneBy(['idAppel' => $appel, 'idFormulaire' => $formulaire]);
            $this->getEm()->remove($tlformAppel);
            $this->addFlash('success', 'Le formulaire ' . $formulaire->getLbFormulaire() . ' vient d\'être supprimer ');
        }
        $this->getEm()->flush();

        return $this->redirectToRoute('assignment_appel_phase', ['idAppel' => $appel->getIdAppel(), 'idPhase' => $idPhase->getIdPhase()]);

    }

    public function accesForm($idFormulaire)
    {


        $formWithAppel = $this->getEm()->getRepository(TlFormulaireAppel::class)->findOneBy(['idFormulaire' => $idFormulaire]);
        $appel = $formWithAppel ? $formWithAppel->getIdAppel() : null;
        if($appel){
            $applInTime = $this->getEmAppPro()->AppelInTimeSlot($formWithAppel->getIdAppel());
return false;
        }
return true;
    }


}
