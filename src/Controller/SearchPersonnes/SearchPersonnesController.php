<?php

namespace App\Controller\SearchPersonnes;

use App\Controller\BaseController;
use App\Entity\TgFavoris;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TrCritere;
use App\Entity\TrLangue;
use App\Entity\User;
use App\Service\SearchPersonnesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchProjetsController.
 */
class SearchPersonnesController extends BaseController
{
    /**
     * @var SearchPersonnesService
     */
    protected $SearchPersonneService;

    /**
     * SearchProjetsController constructor.
     */
    public function __construct(SearchPersonnesService $SearchPersonneService)
    {
        $this->SearchPersonneService = $SearchPersonneService;
    }

    /**
     * @Route("/search/personnes", name="search_personnes")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $referer = $request->headers->get('referer'); // get the referer, it can be empty!
        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('id') ?: null;
            $name = $request->request->get('Name') ?: null;
            $action = $request->request->get('action') ?: null;
            $tgFavoris = $this->getDoctrine()->getRepository(TgFavoris::class)->findOneby(['idFavoris' => $id]);
            $this->SearchPersonneService->UpdateFavoris($tgFavoris, $name, $action);
        }

        $idUser = $this->getUser()->getId();
        $listFavoris = $this->getDoctrine()->getRepository(TgFavoris::class)->findby(['idUser' => $idUser, 'typFavoris' => 'PER']);

        // Filters
        $listFilters = $this->getDoctrine()->getRepository(TrCritere::class)->findby(['typSearch' => 'PER']);

        return $this->render('searchPersonnes/searchPersonne.html.twig', [
            'listFavoris' => $listFavoris,
            'listFilters' => $listFilters,
            'referer' => $referer,
        ]);
    }

    /**
     * @Route("/personnes/favoritesave", name="personnes_favorite_save")
     */
    public function favoriteSave(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $nameRules = $request->request->get('name_rules');
            $rules = $request->request->get('rules');

            if (!$nameRules) {
                $error = 'Le nom est obligatoire';
                $response = new Response(json_encode([
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $error,
                ]));
            } else {
                $idUser = $this->getUserConnect();
                $tgUtilisateur = $this->getDoctrine()->getRepository(User::class)->findOneby(['idPersonne' => $idUser]);
                $typFavoris = 'PER';
                $this->SearchPersonneService->rulesAdd($nameRules, $rules, $tgUtilisateur, $typFavoris);

                $response = new Response(json_encode([
                    'result' => 1,
                    'message' => 'ok',
                    'data' => '',
                ]));
            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/personnes/advancedsearch", name="personnes_advancedsearch")
     */
    public function advancedSearch(Request $request)
    {
        $draw = $request->request->get('draw') ?: 1;
        $row = $request->request->get('start');
        $rowperpage = $request->request->get('length'); // Rows display per page
        $columnIndex = $request->request->get('order'); // Column index
        $columnIndex = $columnIndex[0]['column'];
        $columnName = $request->request->get('columns'); // Column name
        $columnName = $columnName[$columnIndex]['data'];
        $columnSortOrder = $request->request->get('order'); // asc or desc
        $columnSortOrder = $columnSortOrder[0]['dir'];

        $searchValue = $request->request->get('search');  // Search value
        $searchValue = $searchValue['value'];
        //# Custom Field value
        $searchByName = $request->request->get('searchByName');
        $searchByEmail = $request->request->get('searchByEmail');
        $searchByGender = $request->request->get('searchByGender');
        $searchByLangue = $request->request->get('searchByLangue');
        $searchByOrganisme = $request->request->get('searchByOrganisme');
        $searchByMcLibre = $request->request->get('searchByMcLibre');
        $searchByMcErc = $request->request->get('searchByMcErc');
        $searchByComite = $request->request->get('searchByComite');
//        $action = $request->request->get('action');

        //# Search

        $searchQuery = '';
        if ('' != $searchByName) {
            $searchQuery .= " (tg_personne.lbNomUsage like '%".$searchByName."%' ) ";
        }
        if ('' != $searchByEmail) {
            if (!$searchQuery) {
                $searchQuery .= " (tg_utilisateur.email like '%".$searchByEmail."%') ";
            }
            $searchQuery .= "AND (tg_utilisateur.email like '%".$searchByEmail."%') ";
        }
        if ('' != $searchByGender) {
            if (!$searchQuery) {
                $searchQuery .= " (tr_genre.lbLong like '%".$searchByGender."%') ";
            }
            $searchQuery .= "AND (tr_genre.lbLong like '%".$searchByGender."%') ";
        }
        if ('' != $searchByLangue) {
            if (!$searchQuery) {
                $searchQuery .= " (tr_langue.lbLangue like '%".$searchByLangue."%') ";
            }
            $searchQuery .= "AND (tr_langue.lbLangue like '%".$searchByLangue."%') ";
        }
        if ('' != $searchByOrganisme) {
            if (!$searchQuery) {
                $searchQuery .= " (tg_organisme.lbNomFr like '%".$searchByOrganisme."%') ";
            }
            $searchQuery .= "AND (tg_organisme.lbNomFr like '%".$searchByOrganisme."%') ";
        }
        if ('' != $searchByMcLibre) {
            if (!$searchQuery) {
                $searchQuery .= " (tg_mc_libre.lbNom like '%".$searchByMcLibre."%') ";
            }
            $searchQuery .= "AND (tg_mc_libre.lbNom like '%".$searchByMcLibre."%') ";
        }
        if ('' != $searchByMcErc) {
            if (!$searchQuery) {
                $searchQuery .= " (tg_mot_cle_erc.lbNomFr like '%".$searchByMcErc."%') ";
            }
            $searchQuery .= "AND (tg_mot_cle_erc.lbNomFr like '%".$searchByMcErc."%') ";
        }
        if ('' != $searchByComite) {
            if (!$searchQuery) {
                $searchQuery .= " (tg_comite.lbAcr like '%".$searchByComite."%') ";
            }
            $searchQuery .= "AND (tg_comite.lbAcr like '%".$searchByComite."%') ";
        }

        //# search filter
        $filter = $request->request->get('sql');  // Search value

        $personnes = $this->SearchPersonneService->findPersonnes($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, 0);

        $nbrPersonnes = $this->SearchPersonneService->findPersonnes($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, 1);

        $data = [];
        foreach ($personnes as $key => $personne) {
            $organismes = [];
            $mcLibres = [];
            $mcErcs = [];
            if ($personne->getTlPersOrg()) {
                foreach ($personne->getTlPersOrg() as $organisme) {
                    $organismes[] = $organisme->getIdOrganisme()->getLbNomFr();
                }
            }

            if ($personne->getTlPersonneMcLibre()) {
                foreach ($personne->getTlPersonneMcLibre() as $mcLibre) {
                    $mcLibres[] = $mcLibre->getIdMcLibre()->getLbNom();
                }
            }

            if ($personne->getTlPersonneMcErc()) {
                foreach ($personne->getTlPersonneMcErc() as $mcErc) {
                    $mcErcs[] = $mcErc->getIdMcErc()->getLbNomFr();
                }
            }

            $cdLangue = ($personne->getIdPersCps()) ? $personne->getIdPersCps()->getLbLangue() : null;
            $email = $this->getDoctrine()->getRepository(User::class)->findOneby(['idPersonne' => $personne->getIdPersonne()]) ?: '';
            $langue = $this->getDoctrine()->getRepository(TrLangue::class)->findOneby(['cdLangue' => $cdLangue]) ?: '';
            $tgParticipation = $this->getDoctrine()->getRepository(TgParticipation::class)->findOneby(['idPersonne' => $personne->getIdPersonne()]) ?: '';

            $idComite = ($tgParticipation) ? $tgParticipation->getIdComite() : null;
            $idProfil = ($tgParticipation) ? $tgParticipation->getIdProfil() : null;

            $lbProfil = ($idProfil) ? ' / '.$idProfil->getCdProfil() : '';
            $lbComite = ($idComite) ? $idComite->getLbAcr() : '';

            $affectationCes = $lbComite.''.$lbProfil;

            $data[] = [
                'idGenre' => ($personne->getIdGenre()) ? $personne->getIdGenre()->getLbLong() : '',
                'lbNomUsage' => $personne->getLbNomUsage() ?: '',
                'email' => ($email) ? $email->getEmail() : '',
                'lbLangue' => ($langue) ? $langue->getLbLangue() : '',
                'organismes' => $organismes,
                'mcLibres' => $mcLibres,
                'mcErcs' => $mcErcs,
                'lbAcr' => $affectationCes,
                'action' => $personne->getIdPersonne(),
            ];
        }

        $response = new Response(json_encode([
            'draw' => $draw,
            'iTotalRecords' => count($nbrPersonnes),
            'iTotalDisplayRecords' => count($nbrPersonnes),
            'aaData' => $data,
        ]));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @return string
     * @Route ("/module/{personne}" , name="module_pers")
     */
    public function sendPersonneModule(Request $request, TgPersonne $personne)
    {
        if ($request->isXmlHttpRequest()) {
            $linkOrigine = $request->request->get('referer');

            if ($linkOrigine) {


            }

            $response = new Response(json_encode(['personne' => $personne->getIdPersonne(), 'referer' => $linkOrigine])
            );

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
