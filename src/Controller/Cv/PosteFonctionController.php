<?php

namespace App\Controller\Cv;

use App\Controller\BaseController;
use App\Entity\TgAdresse;
use App\Entity\TgIdExternes;
use App\Entity\TgOrganisme;
use App\Entity\TlPersOrg;
use App\Entity\TrFonction;
use App\Entity\TrPays;
use App\Entity\TrTypIdExt;
use App\Form\CvBlocs\CvOrganimePrivFrType;
use App\Form\CvBlocs\CvOrganimePubFrType;
use App\Form\CvBlocs\TgCvFonctionType;
use App\Form\CvBlocs\TgOrganismeType;
use App\Service\OrganismeService;
use App\Service\ReferentielService;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PosteFonctionController.
 *
 * @Route("cv")
 */
class PosteFonctionController extends BaseController
{
    /**
     * @var ReferentielService
     */
    private $referentielService;
    /**
     * @var OrganismeService
     */
    private $organismeService;

    /**
     * PosteFonctionController constructor.
     */
    public function __construct(ReferentielService $referentielService, OrganismeService $organismeService)
    {
        $this->referentielService = $referentielService;
        $this->organismeService = $organismeService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/fonctions/postes", name="cv_fonc_post")
     */
    public function showFonction(Request $request)
    {
        $tgCv = $this->cvCreate();
        $tgOrganisme = new TgOrganisme();
        $tgPersonne = $this->getUserConnect();
        $form_rnsr = $this->createForm(CvOrganimePubFrType::class);
        $form_siret = $this->createForm(CvOrganimePrivFrType::class);
        $form_etr = $this->createForm(TgOrganismeType::class, $tgOrganisme);
//        $adresse = new TgAdresse()

        $form_rnsr->handleRequest($request);
        $form_siret->handleRequest($request);
        $form_etr->handleRequest($request);
        $tgOrganismePub = $this->emRep(TlPersOrg::class)->findBy(['idPersonne' => $tgPersonne, 'typeOraganisme' => 'PUB']);
        $tgOrganismePrv = $this->emRep(TlPersOrg::class)->findBy(['idPersonne' => $tgPersonne, 'typeOraganisme' => 'PRV']);
        $tgOrganismeEtr = $this->emRep(TlPersOrg::class)->findBy(['idPersonne' => $tgPersonne, 'typeOraganisme' => 'ETR']);

        return $this->render('cvPersonne/blocs/poste_fonction/show_poste.html.twig', [
            'form_rnsr' => $form_rnsr->createView(),
            'form_siret' => $form_siret->createView(),
            'form_etr' => $form_etr->createView(),
            'tgOrganismePub' => $tgOrganismePub,
            'tgOrganismePrv' => $tgOrganismePrv,
            'tgOrganismeEtr' => $tgOrganismeEtr,

            'personne' => $this->getUserConnect(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/org/add/cv", name="org_add_cv_poste")
     */
    public function fonctionsCv(Request $request)
    {
        $personne = $this->getUserConnect();
        $tgCv = $this->cvCreate();
        $fonctions = $this->getEm()->getRepository(TrFonction::class)->findAll();

        $orcid = $this->emRep(TgIdExternes::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idTypeRefExt' => 1]);
        $researchID = $this->emRep(TgIdExternes::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idTypeRefExt' => 2]);
        $idHal = $this->emRep(TgIdExternes::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idTypeRefExt' => 3]);
        $idRef = $this->emRep(TgIdExternes::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idTypeRefExt' => 4]);

        $form = $this->createForm(TgCvFonctionType::class, $tgCv, [
            'orcid' => $orcid ? $orcid->getNumIdentifiant() : '',
            'researchID' => $researchID ? $researchID->getNumIdentifiant() : '',
            'idHal' => $idHal ? $idHal->getNumIdentifiant() : '',
            'idRef' => $idRef ? $idRef->getNumIdentifiant() : '',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orcid = $orcid ? $orcid : new TgIdExternes();
            $researchID = $researchID ? $researchID : new TgIdExternes();
            $idHal = $idHal ? $idHal : new TgIdExternes();
            $idRef = $idRef ? $idRef : new TgIdExternes();
            foreach ([1 => $orcid, 2 => $researchID, 3 => $idHal, 4 => $idRef] as $key => $tgIdExt) {
                $trTyp = $this->emRep(TrTypIdExt::class)->find($key);

                if (1 == $key) {
                    $ident = $form->get('orcid')->getData();
                }
                if (2 == $key) {
                    $ident = $form->get('researchID')->getData();
                }
                if (3 == $key) {
                    $ident = $form->get('idHal')->getData();
                }
                if (4 == $key) {
                    $ident = $form->get('idRef')->getData();
                }

                if (null != $tgIdExt->getNumIdentifiant()) {
                    if (null != $ident) {
                        $tgIdExt->setNumIdentifiant($ident);
                        $this->getEm()->persist($tgIdExt);
                    } else {
                        $this->getEm()->remove($tgIdExt);
                        $this->getEm()->flush();
                    }
                } elseif (null == $tgIdExt->getNumIdentifiant() && '' != $ident) {
                    $tgIdExt->setIdPersonne($this->getUserConnect())
                            ->setIdTypeRefExt($trTyp)
                            ->setNumIdentifiant($ident);
                    $this->getEm()->persist($tgIdExt);
                }

                $this->getEm()->flush();
            }
            $tgcv = $this->cvCreate();
            $this->getEm()->persist($tgcv);

            $this->getEm()->flush();
            $this->addFlash('success', 'le curriculum vitae (Fonction) à bien été enregistré');

            return $this->redirectToRoute('org_add_cv_poste', [
            ]);
        }

        return $this->render('cvPersonne/blocs/poste_fonction/fonctions_cv.html.twig', [
            'form_fonct' => $form->createView(),
            'fonctions' => $fonctions,
        ]);
    }

    /**
     * @return Response
     * @Route("/org/pub/fr" , name="cv_org_pub_fr")
     */
    public function addOrganismePubFr(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $trPays = $this->getDoctrine()->getRepository(TrPays::class)
                ->findOneby(['lbPays' => 'France']);

            $rnsr = $request->request->get('rnsr');
            $siret = $request->request->get('siret');
            $name_tut_heb = $request->request->get('name_tut_heb');
            $numero_unite = $request->request->get('code_unite');
            $laboratoire = $request->request->get('laboratoire');
            $adress = $request->request->get('adress_tut_heb');
            $compl_adress = $request->request->get('compl_adress_tut_heb');
            $postal_code = substr($request->request->get('postal_code_tut_heb'), 0, 5);
            $city = $request->request->get('city_tut_heb');
//                $trPays = $request->request->get('country_tut_heb') ?: 250;
            $tutLib = $this->referentielService->getLibelle($name_tut_heb) ?: null;
            $lbSrvice = $sigle = null;

            $responseOrganisme = $this->organismeService
                    ->verifAndAddOrganisme($rnsr, $siret, $tutLib, $laboratoire, $numero_unite, $sigle, $adress, $compl_adress, $postal_code, $city, $trPays);

            $tlPersOrg = $this->emRep(TlPersOrg::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $responseOrganisme]) ?: null;

            !$tlPersOrg ? $this->organismeService->addTlOrgPersonne($responseOrganisme, $this->getUserConnect(), 'PUB', $lbSrvice) : null;

            $response = new Response(json_encode(['success' => $city]));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @return Response
     * @Route("/org/pub/fr/{idOrganisme}" , name="cv_org_pub_fr_show")
     */
    public function showOrganismePubFr(Request $request, TgOrganisme $idOranisme)
    {
        if ($request->isXmlHttpRequest()) {
            $tgOrganisme = $this->emRep(TgOrganisme::class)->find($idOranisme);
            $tutLib = $this->referentielService->getInfosTutelles($tgOrganisme->getSiret()->getSiret()) ?: null;

            $dataShow = [
                'rnsr_puf' => $tgOrganisme->getCdRnsr()->getCdRnsr(),
                'siret_puf' => $tgOrganisme->getSiret()->getSiret(),
//                'delegation_puf' => $tgOrganisme->getLbDeleguation(),
                'name_tut_heb_puf' => $tgOrganisme->getLbNomFr(),
                'laboratoire_puf' => $tgOrganisme->getLbLaboratoire(),
                'code_unite' => $tgOrganisme->getSiret()->getCodeUnite(),
                'adress_tut_heb_puf' => ($tgOrganisme->getCdRnsr()->getIdAdresse()) ? $tgOrganisme->getCdRnsr()->getIdAdresse()->getLbAdresse() : '',
                'compl_adress_tut_heb_puf' => ($tgOrganisme->getCdRnsr()->getIdAdresse()) ? $tgOrganisme->getCdRnsr()->getIdAdresse()->getLbComplAdresses() : '',
                'postal_code_tut_heb_puf' => ($tgOrganisme->getCdRnsr()->getIdAdresse()) ? $tgOrganisme->getCdRnsr()->getIdAdresse()->getCd() : '',
                'city_tut_heb_puf' => ($tgOrganisme->getCdRnsr()->getIdAdresse()) ? $tgOrganisme->getCdRnsr()->getIdAdresse()->getVille() : '',
                'country_tut_heb_puf' => $tgOrganisme->getCdRnsr()->getIdAdresse()->getCdPays()->getLbPays(),
                'idorganisme' => $tgOrganisme->getIdOrganisme(),
            ];

            $response = new Response(json_encode($dataShow));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @return Response
     * @Route("/org/pub/fr/{idOrganisme}/edit" , name="cv_org_pub_fr_edit")
     */
    public function editOrganismePubFr(Request $request, TgOrganisme $tgOrganisme)
    {
        $trPays = $this->getDoctrine()->getRepository(TrPays::class)
            ->findOneby(['lbPays' => 'France']);
        try {
            $rnsr = $request->request->get('rnsr');
            $siret = $request->request->get('siret');
            $name_tut_heb = $request->request->get('name_tut_heb');
            $numero_unite = $request->request->get('code_unite');
            $laboratoire = $request->request->get('laboratoire');
            $adress = $request->request->get('adress_tut_heb');
            $compl_adress = ($request->request->get('compl_adress_tut_heb')) ? $request->request->get('compl_adress_tut_heb') : '';
            $postal_code = substr($request->request->get('postal_code_tut_heb'), 0, 5);
            $city = $request->request->get('city_tut_heb');
            $tutLib = $this->referentielService->getLibelle($name_tut_heb) ?: null;
            $lbSrvice = $sigle = null;

            $tlPersOrg = $this->emRep(TlPersOrg::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $tgOrganisme]);
            $this->getEm()->remove($tlPersOrg);
            $this->getEm()->flush();

            $responseOrganisme = $this->organismeService
                ->verifAndAddOrganisme($rnsr, $siret, $tutLib, $laboratoire, $numero_unite, $sigle, $adress, $compl_adress, $postal_code, $city, $trPays);

            $this->organismeService->addTlOrgPersonne($responseOrganisme, $this->getUserConnect(), 'PUB', null);

            $this->getEm()->flush();
        } catch (\Exception $e) {
            dd($e);
            $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
        }
        $dataShow = 'ok';
        $response = new Response(json_encode($dataShow));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/org/pub/fr/delete/{idOrganisme}" , name="cv_org_pub_fr_delete")
     */
    public function deletOrganismePubFr(Request $request, TgOrganisme $tgOrganisme)
    {
        if ($this->isCsrfTokenValid('delete'.$tgOrganisme->getIdOrganisme(), $request->request->get('_token'))) {
            try {
                $tlPersOrg = $this->getEm()->getRepository(TlPersOrg::class)
                    ->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $tgOrganisme, 'typeOraganisme' => 'PUB']);

                $this->getEm()->remove($tlPersOrg);
                $this->getEm()->flush();

                $this->addFlash('success', "l'organisme a bien été supprimé .");

                return $this->redirect($this->generateUrl('cv_fonc_post'));
            } catch (DBALException $e) {
                $this->addFlash('error', "Impossible de supprimer l'organisme");

                return $this->redirect($this->generateUrl('cv_fonc_post'));
            }
        }

        return $this->render('cvPersonne/blocs/poste_fonction/_form_delete_pub_fr.html.twig', [
            'idOrganisme' => $tgOrganisme->getIdOrganisme(),
        ]);
    }

    /**
     * @return Response
     * @Route("/org/priv/fr" , name="cv_org_priv_fr")
     *
     * */
    public function addOrganismePrivFr(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $trPays = $this->getDoctrine()->getRepository(TrPays::class)->find(250);

            try {
                $siret = $request->request->get('siret_tut_gest');
                $lbSrvice = $request->request->get('service');
                $tutLib = $request->request->get('name_tut_gest');
                $sigle = $request->request->get('sigle');
                $adress = $request->request->get('adress_tut_gest');
                $compl_adress = $request->request->get('compl_adress_tut_gest');
                $postal_code = substr($request->request->get('postal_code_tut_gest'), 0, 5);
                $city = $request->request->get('city_tut_gest');
//                $trPays = $request->request->get('country_tut_gest')?: 250;

                $rnsr = $numero_unite = $laboratoire = null;

                $responseOrganisme = $this->organismeService
                    ->verifAndAddOrganisme($rnsr, $siret, $tutLib, $laboratoire, $numero_unite, $sigle, $adress, $compl_adress, $postal_code, $city, $trPays);

                $tlPersOrg = $this->emRep(TlPersOrg::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $responseOrganisme]) ?: null;

                !$tlPersOrg ? $this->organismeService->addTlOrgPersonne($responseOrganisme, $this->getUserConnect(), 'PRV', $lbSrvice) : null;

                $success = ' L\'organisme à bien été enregistré';
            } catch (\Exception $e) {
                dd($e);
                $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
            }
            $response = new Response(json_encode(['success' => $success]));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @param TgOrganisme $tgOrganisme
     *
     * @return Response
     * @Route("/org/priv/fr/{idOrganisme}" , name="cv_org_priv_fr_show")
     */
    public function showOrganismePrivFr(Request $request, TgOrganisme $idOrganisme)
    {
        if ($request->isXmlHttpRequest()) {
            $tlPersOrg = $this->emRep(TlPersOrg::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $idOrganisme]);
            $tgOrganisme = $this->getEm()->getRepository(TgOrganisme::class)->find($idOrganisme);
            $data = [
                'siret_tut_gest_prf' => $tgOrganisme->getSiret()->getSiret(),
                'service' => $tlPersOrg->getLbService(),
                'name_tut_gest_prf' => $tgOrganisme->getLbNomFr(),
                'sigle_prf' => $tgOrganisme->getSiret()->getSigle(),
                'adress_tut_gest_prf' => $tgOrganisme->getSiret()->getIdAdresse()->getLbAdresse(),
                'compl_adress_tut_gest_prf' => $tgOrganisme->getSiret()->getIdAdresse()->getLbComplAdresses(),
                'postal_code_tut_gest_prf' => $tgOrganisme->getSiret()->getIdAdresse()->getCd(),
                'city_tut_gest_prf' => $tgOrganisme->getSiret()->getIdAdresse()->getVille(),
                'country_tut_gest_prf' => $tgOrganisme->getSiret()->getIdAdresse()->getCdPays()->getLbPays(),
                'idOrganisme' => $tgOrganisme->getIdOrganisme(),
            ];

            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @return Response
     * @Route("/org/priv/fr/{idOrganisme}/edit" , name="cv_org_priv_fr_edit")
     */
    public function editOrganismePrivFr(Request $request, TgOrganisme $tgOrganisme)
    {
        if ($request->isXmlHttpRequest()) {
            $trPays = $this->getDoctrine()->getRepository(TrPays::class)
                ->findOneby(['lbPays' => 'France']);
            try {
                $siret = $request->request->get('siret_tut_gest');
                $service = $request->request->get('service');
                $tutLib = $request->request->get('name_tut_gest');
                $sigle = $request->request->get('sigle');
                $adress = $request->request->get('adress_tut_gest');
                $compl_adress = $request->request->get('compl_adress_tut_gest');
                $postal_code = substr($request->request->get('postal_code_tut_gest'), 0, 5);
                $city = $request->request->get('city_tut_gest');
                $rnsr = $laboratoire = $numero_unite = null;

                $tlPersOrg = $this->emRep(TlPersOrg::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $tgOrganisme]);
                $this->getEm()->remove($tlPersOrg);
                $this->getEm()->flush();

                $responseOrganisme = $this->organismeService
                    ->verifAndAddOrganisme($rnsr, $siret, $tutLib, $laboratoire, $numero_unite, $sigle, $adress, $compl_adress, $postal_code, $city, $trPays);

                $this->organismeService->addTlOrgPersonne($responseOrganisme, $this->getUserConnect(), 'PRV', $service);

                $success = 'ok';
            } catch (\Exception $e) {
                dd($e);
                $this->session->getFlashBag()->add('error', $this->translator->trans('bloc.error.save.project'));
            }
            $response = new Response(json_encode(['success' => $success]));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/org/priv/fr/{idOrganisme}/delete" , name="cv_org_priv_fr_delete")
     */
    public function deleteOrganismePrivFr(Request $request, TgOrganisme $tgOrganisme)
    {
        if ($this->isCsrfTokenValid('delete'.$tgOrganisme->getIdOrganisme(), $request->request->get('_token'))) {
            try {
                $tlPersOrg = $this->getEm()->getRepository(TlPersOrg::class)
                    ->findOneBy(['idPersonne' => $this->getUserConnect(), 'idOrganisme' => $tgOrganisme, 'typeOraganisme' => 'PRV']);

                $this->getEm()->remove($tlPersOrg);
                $this->getEm()->flush();

                $this->addFlash('success', "l'organisme a bien été supprimé .");

                return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismePrivFran');
            } catch (DBALException $e) {
                $this->addFlash('error', "Impossible de supprimer l'organisme");

                return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismePrivFran');
            }
        }

        return $this->render('cvPersonne/blocs/poste_fonction/_form_delete_priv_fr.html.twig', [
            'idOrganisme' => $tgOrganisme->getIdOrganisme(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/org/etr" , name="cv_org_etr")
     */
    public function addOrganismeEtr(Request $request)
    {
        $tgOrganisme = new TgOrganisme();
        $form_etr = $this->createForm(TgOrganismeType::class, $tgOrganisme);
        $form_etr->handleRequest($request);

        if ($form_etr->isSubmitted() && $form_etr->isValid()) {
            $this->getEm()->persist($tgOrganisme);

            $tlPersorg = new TlPersOrg();
            $tlPersorg
                ->setIdOrganisme($tgOrganisme)
                ->setIdPersonne($this->getUserConnect())
                ->setTypeOraganisme('ETR');
            $this->getEm()->persist($tlPersorg);

            $this->getEm()->flush();

            $this->addFlash('success', "l'organisme a bien été enregistré .");

            return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismeEtr');
        }

        return $this->render('cvPersonne/blocs/poste_fonction/_form_add_etr_fr.html.twig', [
            'form_etr' => $form_etr->createView(),
            'action' => $request->getRequestUri(),
        ]);
    }

    /**
     * @return Response
     * @Route("/org/etr/{idOrganisme}/update", name="cv_org_etr_edit")
     */
    public function editOrganismeEtr(Request $request, TgOrganisme $tgOrganisme)
    {
        $form_etr = $this->createForm(TgOrganismeType::class, $tgOrganisme);

        $form_etr->handleRequest($request);
//        dd($form_etr);
        if ($form_etr->isSubmitted() && $form_etr->isValid()) {
            $this->getEm()->persist($tgOrganisme);
            $this->getEm()->flush();

            $this->addFlash('success', "l'organisme a bien été modifié .");

            return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismeEtr');
        }

        return $this->render('cvPersonne/blocs/poste_fonction/_form_add_etr_fr.html.twig', [
            'form_etr' => $form_etr->createView(),
            'actionsId' => 'editOrgEtr',
            'button_label' => 'Modifier',
            'action' => $request->getRequestUri(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/org/etr/{idOrganisme}/delete" , name="cv_org_etr_delete")
     */
    public function deleteOrganismeEtr(Request $request, TgOrganisme $tgOrganisme)
    {
        if ($this->isCsrfTokenValid('delete'.$tgOrganisme->getIdOrganisme(), $request->request->get('_token'))) {
            try {
                $tlPersorg = $this->getEm()->getRepository(TlPersOrg::class)->findOneBy([
                    'idOrganisme' => $tgOrganisme,
                    'idPersonne' => $this->getUserConnect(),
                    'typeOraganisme' => 'ETR',
                    ]);

                $this->getEm()->remove($tlPersorg);
                $this->getEm()->remove($tgOrganisme);

                $this->getEm()->flush();
                $this->addFlash('success', "l'organisme a bien été supprimé .");

                return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismeEtr');
            } catch (DBALException $e) {
                $this->addFlash('error', "Impossible de supprimer l'organisme");

                return $this->redirect($this->generateUrl('cv_fonc_post').'#OrganismeEtr');
            }
        }

        return $this->render('cvPersonne/blocs/poste_fonction/_form_delete_etr.html.twig', [
            'idOrganisme' => $tgOrganisme->getIdOrganisme(),
        ]);
    }
}
