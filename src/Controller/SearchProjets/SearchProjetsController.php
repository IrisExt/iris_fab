<?php


namespace App\Controller\SearchProjets;


use App\Controller\BaseController;
use App\Entity\TgCoordinationProj;
use App\Entity\TgFavoris;
use App\Entity\TgMcLibre;
use App\Entity\TgPartenariat;
use App\Entity\TgParticipation;
use App\Entity\TrCritere;
use App\Entity\TrLangue;
use App\Entity\User;
use App\Service\SearchProjetsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SearchProjetsController
 * @package App\Controller\SearchPersonnes
 */
class SearchProjetsController extends BaseController
{
    /**
     * @var SearchProjetsService
     */
    protected $searchProjetsService;

    /**
     * SearchProjetsController constructor.
     * @param SearchProjetsService $searchProjetsService
     */
    public function __construct(SearchProjetsService $searchProjetsService)
    {
        $this->searchProjetsService = $searchProjetsService;
    }

    /**
     * @Route("/search/projets", name="search_projets")
     */
    public function search(Request $request, SessionInterface $session)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('id') ?: null;
            $name = $request->request->get('Name') ?: null;
            $action = $request->request->get('action') ?: null;
            $tgFavoris = $this->getDoctrine()->getRepository(TgFavoris::class)->findOneby(['idFavoris' => $id]);
            $this->searchProjetsService->UpdateFavoris($tgFavoris, $name, $action);
        }

        $idUser = $this->getUser()->getId();
        $listFavoris = $this->getDoctrine()->getRepository(TgFavoris::class)->findby(['idUser' => $idUser, 'typFavoris' => 'PRO']);

        // Filters
        $listFilters = $this->getDoctrine()->getRepository(TrCritere::class)->findby(['typSearch' => 'PRO']);

        return $this->render('searchProjets/searchProjets.html.twig', [
            'listFavoris' => $listFavoris,
            'listFilters' => $listFilters
        ]);
    }

    /**
     * @Route("/projets/favoritesave", name="projets_favorite_save")
     */
    public function favoriteSave(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $nameRules = $request->request->get('name_rules');
            $rules = $request->request->get('rules');

            if (!$nameRules) {
                $error = "Le nom est obligatoire";
                $response = new Response(json_encode(array(
                    'result' => 0,
                    'message' => 'Invalid form',
                    'data' => $error
                )));

            } else {

                $idUser = $this->getUserConnect();
                $tgUtilisateur = $this->getDoctrine()->getRepository(User::class)->findOneby(['idPersonne' => $idUser]);
                $typFavoris = 'PRO';
                $this->searchProjetsService->rulesAdd($nameRules, $rules, $tgUtilisateur, $typFavoris);

                $response = new Response(json_encode(array(
                    'result' => 1,
                    'message' => 'ok',
                    'data' => ''
                )));

            }

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/projets/advancedsearch", name="projets_advancedsearch")
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
## Custom Field value
        $searchByAppel = $request->request->get('searchByAppel');
        $searchByProjet = $request->request->get('searchByProjet');
        $searchByCoordinateur = $request->request->get('searchByCoordinateur');
        $searchByCps = $request->request->get('searchByCps');
        $searchByOrganisme = $request->request->get('searchByOrganisme');
        $searchByMcLibre = $request->request->get('searchByMcLibre');
        $searchByMcErc = $request->request->get('searchByMcErc');
        $searchByComite = $request->request->get('searchByComite');
        $searchByPartenaire = $request->request->get('searchByPartenaire');
        $searchByFinancement = $request->request->get('searchByFinancement');

## Search

        $searchQuery = '';
        if ($searchByAppel != '') {
            $searchQuery .= " (tg_appel_proj.lbAppel like '%" . $searchByAppel . "%' ) ";
        }
        if ($searchByProjet != '') {
            if(!$searchQuery) $searchQuery .= " (tg_projet.lbAcro like '%" . $searchByProjet . "%') ";
            $searchQuery .= "AND (tg_projet.lbAcro like '%" . $searchByProjet . "%') ";
        }
        if ($searchByCoordinateur != '') {
            if(!$searchQuery) $searchQuery .= " (tg_personne_coord.lbNomUsage like '%" . $searchByCoordinateur . "%') ";
            $searchQuery .= "AND (tg_personne_coord.lbNomUsage like '%" . $searchByCoordinateur . "%') ";
        }
        if ($searchByComite != '') {
            if(!$searchQuery) $searchQuery .= " (tg_comite.lbAcr like '%" . $searchByComite . "%') ";
            $searchQuery .= "AND (tg_comite.lbAcr like '%" . $searchByComite . "%') ";
        }
        if ($searchByCps != '') {
            if(!$searchQuery) $searchQuery .= " (tg_personne_cps.lbNomUsage like '%" . $searchByCps . "%') ";
            $searchQuery .= "AND (tg_personne_cps.lbNomUsage like '%" . $searchByCps . "%') ";
        }
        if ($searchByMcLibre != '') {
            if(!$searchQuery) $searchQuery .= " (tg_mc_libre.lbNom like '%" . $searchByMcLibre . "%') ";
            $searchQuery .= "AND (tg_mc_libre.lbNom like '%" . $searchByMcLibre . "%') ";
        }
        if ($searchByMcErc != '') {
            if(!$searchQuery) $searchQuery .= " (tg_mot_cle_erc.lbNomFr like '%" . $searchByMcErc . "%') ";
            $searchQuery .= "AND (tg_mot_cle_erc.lbNomFr like '%" . $searchByMcErc . "%') ";
        }
        if ($searchByOrganisme != '') {
            if(!$searchQuery) $searchQuery .= " (tg_personne_respsc.lbNomUsage like '%" . $searchByOrganisme . "%') ";
            $searchQuery .= "AND (tg_personne_respsc.lbNomUsage like '%" . $searchByOrganisme . "%') ";
        }
        if ($searchByPartenaire != '') {
            if(!$searchQuery) $searchQuery .= " (tg_organisme.lbNomFr like '%" . $searchByPartenaire . "%') ";
            $searchQuery .= "AND (tg_organisme.lbNomFr like '%" . $searchByPartenaire . "%') ";
        }
        if ($searchByFinancement != '') {
            if(!$searchQuery) $searchQuery .= " (tr_inst_fi.lbNom like '%" . $searchByFinancement . "%') ";
            $searchQuery .= "AND (tr_inst_fi.lbNom like '%" . $searchByFinancement . "%') ";
        }

        ## search filter
        $filter = $request->request->get('sql');  // Search value

        $projets = $this->searchProjetsService->findProjets($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, 0);

        $nbrProjets = $this->searchProjetsService->findProjets($columnName, $columnSortOrder, $row, $rowperpage, $searchValue, $searchQuery, $filter, 1);

        $data = array();
        foreach ($projets as $key => $projet) {

            $tgPartenaires = $this->getDoctrine()->getRepository(TgPartenariat::class)->findby(['idProjet' => $projet->getIdProjet()]) ?: '';
            $partenaires = array();
            $respSc = array();
            if ($tgPartenaires) {
                foreach ($tgPartenaires as $partenaire) {
                    $hebergeur = ($partenaire->getHebergeur()) ? $partenaire->getHebergeur()->getLbNomFr() : '';
                    $tutGest = ($partenaire->getTutGest()) ? $partenaire->getTutGest()->getLbNomFr() : '';
                    $organisme = ($hebergeur) ? $hebergeur : $tutGest;
                  //  $tutGest = ($partenaire->get) ? $partenaire->getGestAdm()->getLbNomUsage() . ' ' . $partenaire->getGestAdm()->getLbPrenom() : '';
                    $respScient = ($partenaire->getRespScient()) ? $partenaire->getRespScient()->getLbNomUsage() . ' ' . $partenaire->getRespScient()->getLbPrenom() . '<br>' : '';
                    if ($organisme && $respScient) $partenaires[] =  $organisme .' - '. $respScient . '<br>';
                    elseif ($organisme && !$respScient) $partenaires[] = $organisme . '<br>';
                    $respSc[] = ($respScient) ? $organisme .' - '. $respScient. '<br>' : '';
                }
            }

            $coodProj = $this->getDoctrine()->getRepository(TgCoordinationProj::class)->findOneby(['idProjet' => $projet->getIdProjet(), 'cdPays' => 250]) ?: '';
            $tgPersonne = ($coodProj) ? $coodProj->getIdPersonne() : '';
            $CoordName = ($tgPersonne) ? $coodProj->getIdPersonne()->getLbNomUsage() : '';
            $coordLastname = ($tgPersonne) ? $coodProj->getIdPersonne()->getLbPrenom() : '';
            $Coordinateur = $CoordName . ' ' . $coordLastname;

            $mcLibres = array();
            $tgMcLibres = $this->getDoctrine()->getRepository(TgMcLibre::class)->findby(['idProjet' => $projet->getIdProjet()]) ?: '';
            if ($tgMcLibres) {
                foreach ($tgMcLibres as $mcLibre) {
                    $mcLibres[] = $mcLibre->getLbNom();
                }
            }

            $mcErcs = array();
            foreach ($projet->getIdMcErc() as $mcErc) {
                $mcErcs[] = $mcErc->getLbNomFr();
            }

            $comite = ($projet->getIdComite()) ? $projet->getIdComite()->getLbAcr() : '';

            $cpsName = ($projet->getPorteur()) ? $projet->getPorteur()->getLbNomUsage() : '';
            $cpsLastName = ($projet->getPorteur()) ? $projet->getPorteur()->getLbPrenom() : '';
            $cps = $cpsName . ' ' . $cpsLastName;

            $data[] = array(
                "lbAppel" => ($projet->getIdAppel()) ? $projet->getIdAppel()->getLbAppel() : '',
                "lbAcro" => $projet->getLbAcro() ?: '',
                "coord" => $Coordinateur,
                "lbAcr" => $comite,
                "cps" => $cps,
                "mcLibres" => $mcLibres,
                "mcErcs" => $mcErcs,
                "respsc" => $respSc,
                "partenaires" => $partenaires,
                "idInfraFi" => ($projet->getIdInfraFi()) ? $projet->getIdInfraFi()->getLbNom() : '',
            );
        }

        $response = new Response(json_encode(array(
            "draw" => $draw,
            "iTotalRecords" => count($nbrProjets),
            "iTotalDisplayRecords" => count($nbrProjets),
            "aaData" => $data
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}