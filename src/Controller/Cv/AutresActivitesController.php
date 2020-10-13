<?php


namespace App\Controller\Cv;


use App\Controller\BaseController;
use App\Entity\TgCv;
use App\Entity\TgOrganisme;
use App\Entity\TgPoste;
use App\Entity\TrFonction;
use App\Form\CvBlocs\TgPosteType;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AutresActivitesController extends BaseController
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/autre-acti", name="cv_autre_activ")
     */
    public function autreActivite(Request $request){

        $tgCvPersonne = $this->cvCreate(); // verifie si le cv est déja crée return (tgCv de personne ou new tgcv)
        $tgCvAutreActiv = ($tgCvPersonne) ? $tgCvPersonne->getLbAutresActivites() : '';


        return $this->render('cvPersonne/blocs/autres_activites/autres_activites.html.twig',
            [
                'text_autre_act' => $tgCvAutreActiv,

            ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/poste-anter", name="cv_poste_anter")
     */
    public function posteAnterieur(Request $request){

        $tgCvPersonne = $this->cvCreate(); // verifie si le cv est déja crée return (tgCv de personne ou new tgcv)
        $tgPoste = $this->getEm()->getRepository(TgPoste::class)->findBy(['idCv' => $tgCvPersonne]);

        return $this->render('cvPersonne/blocs/autres_activites/postes_anterieures.html.twig',
            [
                'postes' => $tgPoste,
            ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("lb_champ", name="cv_add_lb_champ")
     */
    public function addCvLbAutreActivite(Request $request){

        $tgCvPersonne = $this->cvCreate(); // verifie si le cv est déja crée return (tgCv de personne ou new tgcv)
        $tgCvPersonne->setLbAutresActivites($request->request->get('autre_activite'));
        $this->getEm()->persist($tgCvPersonne);
        $this->getEm()->flush();
        $this->addFlash('success',"");
        return $this->redirectToRoute('cv_autre_activ');
    }

    /**
     * @param Request $request
     * @param TgPoste $tgPoste
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/add-autres-acti", name="cv_add_autre_activ")
     */
    public function addAutreActivite(Request $request){

        $tgPoste = new TgPoste();
        $form = $this->createForm(TgPosteType::class, $tgPoste);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $tgOrganisme = new TgOrganisme();
            $tgOrganisme->setLbNomFr($request->request->get('tg_poste')['lbNomFr']);
            $tgOrganisme->setVille($request->request->get('tg_poste')['ville']);
            $this->getEm()->persist($tgOrganisme);

            $tgCv = $this->cvCreate();
            $tgCv->setIdPersonne($this->getUserConnect());
            $this->getEm()->persist($tgCv);


            $tgPoste->setIdOrganisme($tgOrganisme)
                    ->setIdCv($tgCv);
            $this->getEm()->persist($tgPoste);

            $this->getEm()->flush();

            $this->addFlash('success',"Le poste antérieure à bien été ajouté");
            return $this->redirectToRoute('cv_poste_anter');
        }

        return $this->render('cvPersonne/blocs/autres_activites/_form_add_autre_activite.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param int $idOrganisme
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/update-autres-acti/{idOrganisme}", name="cv_update_autre_activ")
     */
    public function updateAutreActivite(Request $request, int $idOrganisme){

        $tgPoste = $this->getEm()->getRepository(TgPoste::class)->findOneBy(['idOrganisme' => $idOrganisme]);
        $tgOrganisme = $this->getEm()->getRepository(TgOrganisme::class)->find($idOrganisme);

        $form = $this->createForm(TgPosteType::class, $tgPoste);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $tgOrganisme->setLbNomFr($request->request->get('tg_poste')['lbNomFr']);
            $tgOrganisme->setVille($request->request->get('tg_poste')['ville']);
            $this->getEm()->persist($tgOrganisme);

            $this->getEm()->persist($tgPoste);
            $this->getEm()->flush();

            $this->addFlash('success',"Le poste antérieure à bien été ajouté");
            return $this->redirectToRoute('cv_poste_anter');
        }

        return $this->render('cvPersonne/blocs/autres_activites/_form_update_autre_activite.html.twig',[
            'form' => $form->createView(),
            'poste' => $tgPoste
        ]);

    }

    /**
     * @param Request $request
     * @param TgOrganisme $tgOrganisme
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/autres-acti/{idOrganisme}/delete", name="cv_delete_autre_activ")
     */
    public function deleteutreActivite(Request $request, TgOrganisme $tgOrganisme)
    {

        if ($this->isCsrfTokenValid('delete' . $tgOrganisme->getIdOrganisme(), $request->request->get('_token'))) {
            try {
                $tgPoste = $this->getEm()->getRepository(TgPoste::class)->findOneBy(['idOrganisme' => $tgOrganisme]);

                $this->getEm()->remove($tgOrganisme);
                $this->getEm()->remove($tgPoste);

                $this->getEm()->flush();
                $this->addFlash('success', "Le poste à bien été supprimé");
                return $this->redirectToRoute('cv_poste_anter');

            } catch (DBALException $e) {
                $this->addFlash('error', "Impossible de supprimer le poste");
                return $this->redirectToRoute('cv_poste_anter');
            }

        }
        return $this->render('cvPersonne/blocs/autres_activites/_form_delete_aut_activ.html.twig', [
            'idOrganisme' => $tgOrganisme->getIdOrganisme()
        ]);
    }

}