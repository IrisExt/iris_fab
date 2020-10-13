<?php

namespace App\Controller\Cv;

use App\Controller\BaseController;
use App\Entity\TgCompetenceLangue;
use App\Entity\TgMotCleErc;
use App\Entity\TgParametre;
use App\Entity\TlPersonneMcErc;
use App\Entity\TrLangue;
use App\Entity\TrNiveauLangue;
use App\Form\CvBlocs\TgCompetenceLangeType;
use App\Form\MotCleErcType;
use App\Repository\TrLangueRepository;
use Doctrine\DBAL\DBALException;
use http\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompetencesLanguesController extends BaseController
{
    /**
     * @var TrLangueRepository
     */
    private $trLangueRepository;

    /**
     * CvPersonneController constructor.
     * @param TrLangueRepository $trLangueRepository
     */
    public function __construct(TrLangueRepository $trLangueRepository)
    {

        $this->trLangueRepository = $trLangueRepository;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/ddrec", name="cv_ddrec")
     */
    public function showCompetencesLangue(Request $request)
    {
        $tgPersonne = $this->getUserConnect();


        $NbErcMax = $this->getDoctrine()->getRepository(TgParametre::class)->findOneBy(['lbCode' => 'NB_MC_ERC_CV_MAX']);
        $NbErcMax = ($NbErcMax) ? $NbErcMax->getLbValeur() : 1;
        $CatMcErcsPes = $this->emRep('App:TrCategorieErc')->McErcPersonnes($this->getUserConnect());
        $CatMcErcsNotPes = $this->emRep('App:TrCategorieErc')->motcleErcAffiche($this->getUserConnect());
        $CatMcErcs = array_merge($CatMcErcsPes, $CatMcErcsNotPes);

        $tlPersonneMcErc = $this->getEm()->getRepository(TlPersonneMcErc::class)->findBy(['idPersonne' => $tgPersonne], ['ordre' => 'DESC']);


        $form = $this->createForm(MotCleErcType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null == $request->request->get('mot_cle_erc')['DataForm']) {
                throw $this->createNotFoundException('Aucun mot clé Erc trouvé');
            }
            try {

                $tlPerMcErc = $this->getEm()->getRepository(TlPersonneMcErc::class)->findBy(['idPersonne' => $tgPersonne]);
                foreach ($tlPerMcErc as $removeMcErc) {
                    $this->getEm()->remove($removeMcErc); // delete all mcErc personne
                }
                $this->getEm()->flush();
                $data = explode(',', $request->request->get('mot_cle_erc')['DataForm']);
                foreach ($data as $key => $blocnum) {
                    $motErc = $this->getEm()->getRepository(TgMotCleErc::class)->find($blocnum);
                    $tlPersonneErc = new TlPersonneMcErc();
                    $tlPersonneErc
                        ->setIdPersonne($tgPersonne)
                        ->setIdMcErc($motErc)
                        ->setOrdre($key + 1);
                    $this->getEm()->persist($tlPersonneErc);
                };
                $this->getEm()->flush();
                $this->addFlash('success', 'Enregistrement des mots clé ERC ');
                return $this->redirectToRoute('cv_ddrec');

            } catch (DBALException $e) {
                $this->addFlash('error', 'Impossible de mettre à jour les mots clés ERC');
                return $this->redirectToRoute('cv_ddrec');
            }
        };

        return $this->render('cvPersonne/blocs/tg_competence_langue/domaineRechercheCompetences.html.twig', [
            'personne' => $this->getUserConnect(),
            'CatMcErcs' => $CatMcErcs,
            'NbErcMax' => $NbErcMax,
            'tlPersonneMcErc' => $tlPersonneMcErc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/ddre/langue" , name="cv_langues")
     */
    public function languesCv(){
        $tgPersonne = $this->getUserConnect();
        $tgCompetenceLnague = $this->getEm()->getRepository(TgCompetenceLangue::class)->findBy(['idPersonne' => $tgPersonne], ['idPratiqueLangue' => 'ASC']);

        return $this->render('cvPersonne/blocs/tg_competence_langue/langues.html.twig',[
            'competences' => $tgCompetenceLnague,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cv/add/ddrec", name="cv_add_ddrec", methods={"post"})
     * show and update competencesLangue
     */
    public function newCompetencesLangue(Request $request)
    {

        $tgPersonne = $this->getUserConnect();
        $trlangue = $this->getEm()->getRepository(TrLangue::class)->findAll();
        $tgCompetence = $this->getEm()->getRepository(TgCompetenceLangue::class)->findBy(
            ['idPersonne' => $tgPersonne], ['idPratiqueLangue' => 'ASC']);
        $langueNotInUser = $this->trLangueRepository->findCompetenceLangueNotInUser($tgPersonne);
        $langue = ($tgCompetence) ? $langueNotInUser : $trlangue;

        $tgCompetenceLangue = new TgCompetenceLangue();
        $form = $this->createForm(TgCompetenceLangeType::class, $tgCompetenceLangue,
            [
                // liste des langues qui reste à ajouter
                'langue_add' => $langue,
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tgCompetenceLangue->setIdPersonne($tgPersonne);
            $this->getEm()->persist($tgCompetenceLangue);
            $this->getEm()->flush();

            $this->addFlash('success', 'Une compétence langue à bien été ajoutée. ');

            return $this->redirectToRoute('cv_langues');
        }

        return $this->render('cvPersonne/blocs/tg_competence_langue/_form_add.html.twig', [
            'form' => $form->createView(),
            'langueNotInUser' => $langue
        ]);
    }

    /**
     * @Route("/cv/{idCompetenceLangue}/update" , name="cv_update_ddrec", methods={"post"})
     */
    public function updateCompetencesLangues(Request $request, TgCompetenceLangue $idCompetenceLangue)
    {

        $tgPersonne = $this->getUserConnect();
        $trlangue = $this->getEm()->getRepository(TrLangue::class)->findAll();
        $tgCompetence = $this->getEm()->getRepository(TgCompetenceLangue::class)->findBy(
            ['idPersonne' => $tgPersonne], ['idPratiqueLangue' => 'ASC']);
        $langueNotInUser = $this->trLangueRepository->findCompetenceLangueNotInUser($tgPersonne);
        $langue = ($tgCompetence) ? $langueNotInUser : $trlangue;
        $form = $this->createForm(TgCompetenceLangeType::class, $idCompetenceLangue,
            [
//                 liste des langues qui reste à ajouter
                'langue_add' => $langue,
                'langue_update' => $idCompetenceLangue->getIdLangue(),
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($idCompetenceLangue);
            $this->getEm()->flush();
            $this->addFlash('success', 'la compétence langue à bien été modifiée. ');

            return $this->redirectToRoute('cv_langues');

        }
        return $this->render('cvPersonne/blocs/tg_competence_langue/_form_update.html.twig', [
            'form' => $form->createView(),
            'idCompetenceLangue' => $idCompetenceLangue->getIdPratiqueLangue()
        ]);

    }

    /**
     * @Route("/cv/{idCompetenceLangue}/delete" , name="cv_delete_ddrec", methods={"DELETE"})
     */
    public function deleteCompetenceLangue(Request $request, TgCompetenceLangue $idCompetenceLangue)
    {
        if ($this->isCsrfTokenValid('delete' . $idCompetenceLangue->getIdPratiqueLangue(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($idCompetenceLangue);
                $this->getEm()->flush();
                $this->addFlash('success', 'la compétence langue à bien été supprimée. ');

                return $this->redirectToRoute('cv_langues');
            } catch (DBALException $e) {

                $this->addFlash('error', 'Impossible de supprimer la compétence langue');

                return $this->redirectToRoute('cv_langues');
            }
        }
        return $this->render('cvPersonne/blocs/tg_competence_langue/_form_delete.html.twig', [

            'idCompetenceLangue' => $idCompetenceLangue->getIdPratiqueLangue()
        ]);
    }

    public function orderTlPersonneMcErc()
    {
        $tgPersonne = $this->getUserConnect();
        $tlPersonneMcErc = $this->getEm()->getRepository(TlPersonneMcErc::class)->findBy(['idPersonne' => $tgPersonne], ['ordre' => 'DESC']);

        return $this->render('cvPersonne/blocs/order_pers_mot_cle.html.twig', [
            'tlPersonneMcErcs' => $tlPersonneMcErc
        ]);

    }
}
