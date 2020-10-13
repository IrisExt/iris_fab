<?php
namespace App\Controller;

use App\Entity\RecherchePersonneCes;
use App\Form\RecherchePersonneCesType;
use App\Repository\RecherchePersonneCesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecherchePersonneCesController
 * @package App\Controller
 * @Route("/recherche")
 */
class RecherchePersonneCesController extends AbstractController
{
//    private $repository;
//    private $em;
//
//    public function __construct(RecherchePersonneCesRepository $repository, ObjectManager $em)
//    {
//        $this->repository = $repository;
//            $this->em = $em;
//    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre", name="recherche_memebre")
     */
    public function recherche(PaginatorInterface $paginator , Request $request): Response{

        $recherche = new RecherchePersonneCes();

        $form = $this->createForm(RecherchePersonneCesType::class);
        $form->handleRequest($request);

//         $personneTrouve = $paginator->paginate(
//             $repository->findPersonneRecherche($recherche),
//             $request->query->getInt('page', 1), /*page number*/
//            4 /*limit per page*/
//        );

        return $this->render('constitutionces/recherche.html.twig',
            [
                'form' => $form->createView()
            ]);

    }
}