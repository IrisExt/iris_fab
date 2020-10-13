<?php


namespace App\Controller;

use App\Entity\TgCourriel;
use App\Entity\TrCatModele;
use App\Form\TgCourrielType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MailGeneriqueControlle
 * @package App\Controller
 * @Route("/mail/generique")
 */
class MailGeneriqueControlle extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/create" , name="create_generique")
     */
    public function createModelMail(Request $request)
    {
        $courriel = new TgCourriel();
        $form = $this->createForm(TgCourrielType::class, $courriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->getEm()->persist($courriel);
                $this->getEm()->flush();
                $this->addFlash('success','Le modèle de courriel est enregistré.');
        }
        return $this->render('mail_generique/modele_doc.html.twig',[
            'courriel' => $courriel,
            'form' => $form->createView(),
    ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/search" , name="search_model")
     */
    public function searchModelMail(Request $request){

        $courriels = $this->getEm()->getRepository(TgCourriel::class)->findAll();
        $categories = $this->getEm()->getRepository(TrCatModele::class)->findAll();
        if ( $request->isXmlHttpRequest() ) {


            $catModel = !empty($request->request->get('cat_modele'))
                ? $this->getEm()->getRepository(TrCatModele::class)->find($request->request->get('cat_modele')):null;

            $idCourriels = [$request->request->get('designation'), $request->request->get('objet')];
            $courrier_des =   $this->getEm()->getRepository(TgCourriel::class)
             ->findCourrielSearch($idCourriels,$catModel);
            dd($courrier_des);

            $response = new Response(json_encode(array(
                'result' => 0,
                'message' => 'Invalid form',
                'courrier_des' => 'Invalid form',
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return $this->render('mail_generique/cherche_modele.html.twig',[
            'courriels' => $courriels,
            'categories' => $categories,
            'result' => 0,
        ]);

    }

}