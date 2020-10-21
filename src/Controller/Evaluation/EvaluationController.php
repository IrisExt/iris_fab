<?php

namespace App\Controller\Evaluation;

use App\Controller\BaseController;
use App\Entity\TgComite;
use App\Entity\TgProjet;
use App\Entity\TgPersonne;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\AffectationManager;
use App\Manager\TlStsEvaluationManager;
use App\Manager\FtCommandeAppManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\StringHelperTrait;
use Symfony\Component\HttpFoundation\JsonResponse;

class EvaluationController extends BaseController
{
    use StringHelperTrait;

    /**
     * @Route("/evaluations/{idComite}", name="evaluations")
     */
    public function index(Request $request, AffectationManager $affectationManager, TlStsEvaluationManager $tlStsEvaluationManager, TgComite $tgComite)
    {
        //dd($tgComite);
        $lstProjets = $affectationManager->getComiteProjets($tgComite);
        $listeProjet = [];
        foreach ($lstProjets as $projet) {
            $projetWithCriticite = $affectationManager->setCriticiteToProject($projet);
            array_push($listeProjet, $projetWithCriticite);
        }

        $statusEvaluations = $tlStsEvaluationManager->getAllStsEvaluations();

        return $this->render('evaluation/evaluation/index.html.twig', [
            'projets' => $listeProjet,
            'statusEvaluations' => $statusEvaluations,
        ]);
    }

    /**
     * @Route("/evaluations/project/{idProjet}", name="evaluations_project")
     */
    public function showProject(TlStsEvaluationManager $tlStsEvaluationManager, TgProjet $tgProjet)
    {
        if (!$tgProjet) {
            throw $this->createNotFoundException('Le projet n\'existe pas');
        }

        $statusEvaluations = $tlStsEvaluationManager->getAllStsEvaluations();

        return $this->render('evaluation/evaluation/project.html.twig', [
            'statusEvaluations' => $statusEvaluations,
            'project' => $tgProjet
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/get_ajax_date_rendu", name="get_ajax_date_rendu", methods={"GET","POST"})
     */
    public function getDateRendu (Request $request, AffectationManager $affectationManager) {
        $tgPersonne = $this->getEm()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $request->request->get('idPersonne')]);

        return $this->render('evaluation/evaluation/modal/date_rendu.html.twig', [
                'tgPersonne'=> $tgPersonne,
                'idProjet' => $request->request->get('idProjet'),
                'idAffectation' => $request->request->get('idAffectation')
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/set_ajax_date_rendu", name="set_ajax_date_rendu", methods={"GET","POST"})
     */
    public function setDateRendu (Request $request, AffectationManager $affectationManager) {
        //$tgPersonne = $this->getEm()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $request->request->get('idPersonne')]);
        //$project = $affectationManager->getProject(55);
        $success = $affectationManager->setDateRendu($request->request->get('idPersonne'), $request->request->get('idAffectation'), $request->request->get('dhRendu'));

        return new JsonResponse([ 'success' => $success]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/get_ajax_comment", name="get_ajax_comment", methods={"GET","POST"})
     */
    public function getComments (Request $request, AffectationManager $affectationManager, FtCommandeAppManager $ftCommandeAppManager) {
        $tgPersonne = $this->getEm()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $request->request->get('idPersonne')]);
        $personneComments = $ftCommandeAppManager->getPersonneComments($request->request->get('idProjet'));
        $project = $affectationManager->getProject($request->request->get('idProjet'));

        return $this->render('evaluation/evaluation/modal/comments.html.twig', [
                'project'=> $project,
                'tgPersonne'=> $tgPersonne,
                'personneComments' => $personneComments
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/set_ajax_comment", name="set_ajax_comment", methods={"GET","POST"})
     */
    public function setComment (Request $request, AffectationManager $affectationManager, FtCommandeAppManager $ftCommandeAppManager) {
        $user = $this->getUserConnect();
        $tgPersonne = $this->getEm()->getRepository(TgPersonne::class)->findOneBy(['idPersonne' => $user->getIdPersonne()]);

        $project = $affectationManager->getProject($request->request->get('idProjet'));
        $success = $ftCommandeAppManager->setPersonneComment($tgPersonne, $project, $request->request->get('comment'));
        $personneComments = $ftCommandeAppManager->getPersonneComments($request->request->get('idProjet'), 'AJAX');

        return new JsonResponse([
            'success' => $success,
            'comments' => $personneComments
        ]);
    }
}
