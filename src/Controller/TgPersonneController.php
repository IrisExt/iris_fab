<?php

namespace App\Controller;

use App\Entity\RecherchePersonneCes;
use App\Entity\TgComite;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TrProfil;
use App\Entity\User;
use App\Form\PersonneSearchType;
use App\Form\RecherchePersonneCesType;
use App\Form\TgPersonneType;
use App\Repository\PersonneRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/personne")
 */
class TgPersonneController extends BaseController
{

    private $repository;
    private $em;
    public function __construct(PersonneRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="tg_personne_index", methods={"GET"})
     */
    public function index(PersonneRepository $personneRepository): Response
    {
        $personnes = $personneRepository->findAll();

        return $this->render('tg_personne/index.html.twig', [
            'tg_personnes' => $personnes,
        ]);
    }

    /**
     * @Route("/new", name="tg_personne_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $tgPersonne = new TgPersonne();
        $form = $this->createForm(TgPersonneType::class, $tgPersonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tgPersonne);

            if(null != $tgPersonne->getUsers()){  //si le User est  défini
                $user = $tgPersonne->getUsers();  //  user affecté à la personne
                $user->setIdPersonne($tgPersonne);
                $entityManager->persist($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('tg_personne_index');
        }

        return $this->render('tg_personne/new.html.twig', [
            'tg_personne' => $tgPersonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPersonne}", name="tg_personne_show", methods={"GET"})
     */
    public function show(TgPersonne $tgPersonne): Response
    {
        return $this->render('tg_personne/show.html.twig', [
            'tg_personne' => $tgPersonne,
        ]);
    }

    /**
     * @Route("/{idPersonne}/edit", name="tg_personne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TgPersonne $tgPersonne): Response
    {
        $user = new User();


        $form = $this->createForm(TgPersonneType::class, $tgPersonne,
            [
                'idpersonne' => $tgPersonne->getUsers()
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if(null != $tgPersonne->getUsers()){  //si le User est  défini
                $user = $tgPersonne->getUsers();  //  user affecté à la personne
                $user->setIdPersonne($tgPersonne);
                $entityManager->persist($user);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tg_personne_index', [
                'idPersonne' => $tgPersonne->getIdPersonne(),
            ]);
        }

        return $this->render('tg_personne/edit.html.twig', [
            'tg_personne' => $tgPersonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPersonne}", name="tg_personne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TgPersonne $tgPersonne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tgPersonne->getIdPersonne(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tgPersonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tg_personne_index');
    }

    public function profilUtilisateur() : string
    {
        return $this->getUser()->getIdPersonne()->getIdProfil()->getLbProfil();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recherche/membre/{idComite}", name="recherche_memebre")
     *
     */
    public function recherche(PaginatorInterface $paginator , Request $request, TgComite $comite): Response
    {

        $this->appelClos($request->getSession()->get('appel')); // retour 403 / Accès refusé si appel est clos

        $recherche = new RecherchePersonneCes();

        $form = $this->createForm(RecherchePersonneCesType::class, $recherche, [
            'comite' => $comite // valeur a envoyer
        ]);
        $profil = $this->getDoctrine()->getRepository(TrProfil::class)->profilMmbreVp();

        $form->handleRequest($request);

        if($form->getData()){
            $totoal =   $this->repository->findPersonneRecherche($request, $comite);
            $personneTrouve = $paginator->paginate(
                $this->repository->findPersonneRecherche($request, $comite),
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );
        }else{
            $personneTrouve = null;
        }

        return $this->render('constitutionces/recherche.html.twig',
            [
                'nb' => count($totoal->getArrayResult()),
                'profils' => $profil,
                'personnes' => $personneTrouve,
                'idcomite' => $comite->getIdComite(),
                'form' => $form->createView()
            ]);

    }

    /**
     * @Route ("/search/set_ajax_personne", name="set_ajax_personne")
     * @param Request $request
     * @return JsonResponse
     */
    public function setPersonneAjax(Request $request): JsonResponse
    {
        $requestString = $request->get('q');
        $personnes = $this->em->getRepository(TgPersonne::class)->findPersonnesByString($requestString);
        if(!$personnes) {
            $result = null;
        }else{
            $result = $this->getPersonnesSelect($personnes);
        }
        return new JsonResponse($result);
    }

    /**
     * @param $personnes
     * @return mixed
     */
    private function getPersonnesSelect($personnes){

        foreach ($personnes as $personne){
            $resultArray[] = [
                'id' => $personne->getIdPersonne(),
                'text' => $personne->getLbNomUsage().' '.$personne->getLbPrenom(),
            ];
        }
        return $resultArray;
    }
}
