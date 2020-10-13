<?php


namespace App\Controller;

use Algolia\SearchBundle\SearchService;
use App\Entity\TgPersonne;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SearchIrisController extends BaseController
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("recherche")
     */
    public function searchPersonne(){

        $personne = new TgPersonne();

        $em = $this->get('doctrine')->getManager();
//        $testa = $this->searchService->count($this->getEm(),  $personne,'');
        $testa = $this->searchService->search($em, $personne,'');
        $normalizer = new ObjectNormalizer();
        $result = $normalizer->normalize($testa, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);


    }
}