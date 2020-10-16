<?php


namespace App\Controller\Cv;


use App\Controller\BaseController;
use App\Entity\TgCv;
use App\Entity\TgDispoExp;
use App\Form\CvBlocs\DisponibleType;
use App\Form\TgCompetenceLangeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CvPersonneController
 * @package App\Controller
 * @Route("cv")
 */
class CvPersonneController extends BaseController
{

    /**
     * @Route("/show", name="cv_show")
     */
    public function showCv(){

        return $this->render('cvPersonne/disponibilite.html.twig');

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dispo", name="cv_dispo")
     */
    public function disponibiliteCv(Request $request){

        $tgPersonne = $this->getUserConnect();
        $appelEnCours = ($this->AppelEncoursAAPG()) ? $this->AppelEncoursAAPG()[0] : '';
        $tgCvPersonne = $this->getEm()->getRepository(TgCv::class)->findOneBy(['idPersonne' => $this->getUserConnect()]);
        $tgCv = ($tgCvPersonne) ? $tgCvPersonne : new TgCv();
        $tgDispoExp = $this->getEm()->getRepository(TgDispoExp::class)
            ->findOneBy(['idPersonne' => $this->getUserConnect(), 'idAppel'=> $appelEnCours ]);
        $tgDispo =  ($tgDispoExp) ? $tgDispoExp : new TgDispoExp();

        $form = $this->createForm(DisponibleType::class,null,
            [
                'dispoComite' => $tgPersonne->getIdDispoComite(),
                'dispoExp' => $tgDispo->getIdChoixExpert()
            ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $tgPersonne->setIdDispoComite($form->getData()['choixComite']);
            $tgPersonne->setIdCv($tgCv);
            $this->getEm()->persist($tgPersonne);

            $tgCv->setIdPersonne($tgPersonne);
            $this->getEm()->persist($tgCv);

            $tgDispo->setIdAppel($appelEnCours);
            $tgDispo->setIdPersonne($tgPersonne)
                    ->setIdChoixExpert($form->getData()['choixDispoExp']);
            $this->getEm()->persist($tgDispo);

            $this->getEm()->flush();
            $this->addFlash('success', 'les disponibilités ont bien été enregistrées');
        }
        return $this->render('cvPersonne/blocs/disponibilite.html.twig', [
                'form' => $form->createView(),
                'appel'=> $appelEnCours
                ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/get_cv_personne", name="get_cv_personne", methods={"GET","POST"})
     */
    public function getCvPerson (Request $request) {
        $tgCvPersonne = $this->getEm()->getRepository(TgCv::class)->findOneBy(['idPersonne' => $request->request->get('idPersonne')]);
        $tgPersonne = $this->getEm()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $request->request->get('idPersonne')]);

        if (!$tgCvPersonne) {
            $tgCvPersonne = $this->getEm()->getRepository(TgCv::class)->findOneBy(['idPersonne' => 12]);
        }

        return $this->render('evaluation/evaluation/modal/cv_personne.html.twig', [
                'cvPersonne'=> $tgCvPersonne,
                'tgPersonne'=> $tgPersonne,
            ]
        );
    }
}