<?php


namespace App\Controller;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgCv;
use App\Entity\TgHabilitation;
use App\Entity\TgParticipation;
use App\Entity\TgPhase;
use App\Entity\TrProfil;
use App\Manager\CoreManagerInterface;
use http\Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


abstract class BaseController extends AbstractController
{

    public function getEm()
    {


        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    /**
     * @param $entity
     * @return \Doctrine\Persistence\ObjectRepository
     * return entityManager getRepository
     */
    public function emRep($entity){
        return $this->getDoctrine()->getRepository($entity);
    }

    /**
     * @param null $comite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectByRole(TgComite $comite = null, string $redirect = null)
    {
        if($redirect){
            return $this->redirectToRoute($redirect,['idComite' => $comite->getIdComite()]);
        }
        switch (true) {
            case $this->isGranted('ROLE_PRES'):
                return $this->redirectToRoute('lst_membre',['idComite' => $comite->getIdComite()]);
            case  $this->isGranted('ROLE_CPS'):
                return $this->redirectToRoute('list_comite',['cmte' => $comite->getIdComite()]);
            default:
                return $this->redirectToRoute('list_comite');
        }
    }

    public function appelClos($appel){
        $statut = $this->getEmAppPro()->findDateClosAppel($appel);
        if(empty($statut)){
            throw new \Exception('Cet appel à projet est fermé',404);
        };
        return true;
    }

    public function getUserConnect()
    {

        if(null == $this->getUser()){
            return $this->render('accueil.html.twig');
        }
        return $this->getUser()->getIdPersonne();
    }

    public function getEmHabil(){
        return $this->getEm()->getRepository(TgHabilitation::class);
    }

    public function getEmAppPro(){
        return $this->getEm()->getRepository(TgAppelProj::class);
    }

    public function getEmPhase(){
        return $this->getEm()->getRepository(TgPhase::class);
    }

    public function getEmComite(){
        return $this->getEm()->getRepository(TgComite::class);
    }

    public function getEmPartic(){
        return $this->getEm()->getRepository(TgParticipation::class);
    }

    public function getEmProfil($id){
        return $this->getEm()->getRepository(TrProfil::class)->find($id);
    }

    /**
     * @param $appel
     * @return bool
     * status of call
     */
    public function APstatut($appel): bool
    {
        $statut = $this->getEmAppPro()->findDateClosAppel($appel);
        $statutAP = !empty($statut);

        return $statutAP;

    }

    public function AppelEncoursAAPG(){
        return $this->getEmAppPro()->findAllAppelEnCours();
    }

    public function cvCreate(){
        $tgCv = $this->getEm()->getRepository(TgCv::class)->findOneBy(['idPersonne' => $this->getUserConnect()]);
        if(!$tgCv){
            $tgCv = new TgCv();
            $tgCv->setIdPersonne($this->getUserConnect());
        }
        return $tgCv;
    }

    public function habilitationsPersonne($profil){
        return $this->getEm()->getRepository(TgHabilitation::class)->findOneBy([
            'idPersonne'=>  $this->getUserConnect(),
            'idProfil' => $profil,
            'blSupprime' => 1
        ]);
    }

    public function profilEntity(int $idProfil){
       return $this->getEm()->getRepository(TrProfil::class)->find($idProfil);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateToken($nbr)
    {
        return rtrim(strtr(base64_encode(random_bytes($nbr)), '+/', '-_'), '=');
    }
}