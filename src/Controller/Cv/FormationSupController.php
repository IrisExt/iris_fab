<?php


namespace App\Controller\Cv;

use App\Controller\BaseController;
use App\Entity\TgPublication;
use App\Entity\TlCvPubl;
use App\Form\CvBlocs\CvFormationsType;
use DateTime;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class FormationSupController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/cv/formation/these", name="cv_formation_sup")
     */
    public function formationSoutenenace(Request $request){

        $tgCv = $this->cvCreate();

        $form = $this->createForm(CvFormationsType::class, $tgCv,[
            'diplome' => $tgCv->getDiplomeAcademique(),
//            'distinction' => $tgCv->getLbDistinction(),
            'dtSoutenance' =>  $tgCv->getDtSoutenanceDeThese()
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()) {

            $date = $request->request->get('cv_formations')['dtSoutenanceDeThese'];
            $dateThese = null;
            if ($date) {
            $dateConvert = date('Y-m-d', strtotime(str_replace('/', '-', '01/' . $date)));//convertir mm/yyyy to dd/mm/yyyy
            $dateThese =  new DateTime($dateConvert);
            }
              $tgCv->setDtSoutenanceDeThese($dateThese)
                 ->setDiplomeAcademique($request->request->get('cv_formations')['diplomeAcademique']);
//                 ->setLbDistinction($request->request->get('cv_formations')['lbDistinction']);
            $this->getEm()->persist($tgCv);
            $this->getEm()->flush();

            $this->addFlash("success", "la formation supérieure à bien été enregistré");

            return $this->redirectToRoute('cv_formation_sup');
        }

        return $this->render('cvPersonne/blocs/formation_sup/show_form_soutenance.html.twig',[
            'formation_cv' => $tgCv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cv/formation/proj", name="cv_projet_recherche")
     */
    public function projetsDeRecherche(Request $request){
        $tgCv = $this->cvCreate();

        $form = $this->createForm(CvFormationsType::class, $tgCv,[
            'proj' => true,
            'distinction' => $tgCv->getLbDistinction(),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $tgCv
                 ->setLbDistinction($request->request->get('cv_formations')['lbDistinction']);
            $this->getEm()->persist($tgCv);
            $this->getEm()->flush();

            $this->addFlash("success", "Projets de recherche, finances, récompenses ... ont bien été enregistrés");

            return $this->redirectToRoute('cv_projet_recherche');

        }
        return $this->render('cvPersonne/blocs/formation_sup/projet_recherche.html.twig',[
            'formation_cv' => $tgCv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/publication", name="cv_publications")
     */
    public function publicationMajeurs(Request $request){
        $tgCv = $this->cvCreate();

        $tlCvPubls = $this->getEm()->getRepository(TlCvPubl::class)->findBy(['idCv' => $tgCv],['ordre' => 'ASC']);
        if('POST' == $request->getMethod()){
            try {
                $keyOrdre = 1;
                foreach ($tlCvPubls as $tlCvPubl) {
                    $publi = $this->getEm()->getRepository(TgPublication::class)
                        ->findOneBy(['idPublication' => $tlCvPubl->getIdPublication()]);
                    $this->getEm()->remove($publi);
                    $this->getEm()->remove($tlCvPubl);
                }

                $lbTitreArray = $request->request->get('lb_titre');
                $lbJustificatifArray = $request->request->get('lb_justificatif');
                if($lbTitreArray) {
                    foreach ($lbTitreArray as $key => $lbTitre) {
                        $tgPulication = new TgPublication();
                        $tgPulication->setLbTitre($lbTitre)
                            ->setLbJustification($lbJustificatifArray[$key]);
                        $this->getEm()->persist($tgPulication);
                        $tlCvPublNew = new TlCvPubl();
                        $tlCvPublNew->setIdCv($tgCv)
                            ->setIdPublication($tgPulication)
                            ->setOrdre($keyOrdre);
                        $this->getEm()->persist($tlCvPublNew);
                        $keyOrdre++;
                    }
                }
                $this->getEm()->flush();
                $this->addFlash("success", "les publications ont bien été validées");

                return $this->redirectToRoute('cv_publications');
            } catch (DBALException $e) {
                $this->addFlash('error', "Impossible d'ajouter la publication");
                return $this->redirectToRoute('cv_publications');
            }
        }
        return $this->render('cvPersonne/blocs/formation_sup/show_publications.html.twig',[
            'tlPubl' => $tlCvPubls,
            'order_max' => 5
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/valorisation", name="cv_valo")
     */
    public function valoradisation(Request $request){

        $tgCv = $this->cvCreate();
        $form = $this->createForm(CvFormationsType::class, $tgCv, [
            'valo' => true,
            'lbValo' => $tgCv->getLbValorisation()
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->getEm()->persist($tgCv);

            $this->getEm()->flush();
            $this->addFlash("success", "les valorisations ont bien été ajoutées");
        }
        return $this->render('cvPersonne/blocs/formation_sup/show_form_valo.html.twig',
            [
                'form' => $form->createView() ,
                'tgCv' =>$tgCv
            ]);
    }
}