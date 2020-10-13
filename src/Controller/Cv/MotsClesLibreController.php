<?php


namespace App\Controller\Cv;


use App\Controller\BaseController;
use App\Entity\TgMcLibre;
use App\Entity\TgMotCleCv;
use App\Entity\TgParametre;
use App\Entity\TlPersonneMclibre;
use App\Repository\TgMotCleCvRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MotsClesLibreController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/mots/cles/libres", name="cv_mot_cle_libre")
     */
    public function addMotsClesLibre(Request $request){
        $tgPersonne = $this->getUserConnect();

        $NbMotLibre = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['lbCode' => 'NB_MC_LIBRE_CV_MAX']);
        $NbMotLibreMax = ($NbMotLibre) ? $NbMotLibre->getLbValeur() : 5;
        $mcLibres = $this->getEm()->getRepository(TgMotCleCv::class)->findBy(['idPersonne' => $tgPersonne], ['ordre' => 'ASC']);
        $maxOrdre = $this->getEm()->getRepository(TgMotCleCv::class)->findMaxOrdreMcLibrePers($tgPersonne);

        if($request->getMethod() == 'POST' ){
//        dd($request);
            foreach ($mcLibres as $removeMcLibre) {
                $this->getEm()->remove($removeMcLibre); // delete all mcErc personne
            }
            $keyOrdre = 1;
			if(!empty($request->request->get('an-input-fr'))){
				foreach ($request->request->get('an-input-fr') as $key => $mcLibreFr){

				   $tgMcLibre = new TgMotCleCv();
					$tgMcLibre
						->setIdPersonne($tgPersonne)
						->setLbMcFr($mcLibreFr)
						->setLbMcEn($request->request->get('an-input-an')[$key])
						->setOrdre($keyOrdre);

					$this->getEm()->persist($tgMcLibre);
					$keyOrdre++;
				}
			}
            $this->getEm()->flush();
            $this->addFlash('success', 'Vos mots clés libres ont bien été enregistrés');
            return $this->redirect($request->headers->get('referer'));
        }
        return $this->render('cvPersonne/blocs/mot_cle_libre/mot_cle_libre.html.twig',[
            'count_row' => $NbMotLibreMax,
            'order_max' => $maxOrdre['max_ordre'],
            'mcLibres' => $mcLibres,
            'count' => 0
        ]);
    }

}