<?php


namespace App\Twig;

use App\Entity\TgAffectation;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use FilterIterator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Role\Role;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig_Environment;
use Twig_Extension;


class AppExtension extends Twig_Extension{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('diffEchance', [$this, 'echeanceDiff'], ['needs_environment' => true]),
            new TwigFunction('extDomain', [$this, 'extraitDomainMail']),
            new TwigFunction('accesMenuRole',[$this, 'accesWithHabMenu']),
            new TwigFunction('notDefined' ,[$this, 'NonDefinedVar'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sortEval', [$this, 'sortEvaluations'])
        ];
    }

    /**
     * @param Twig_Environment $env  a Twig_Environment instance
     * @param string|DateTime  $date a string or DateTime object to convert
     * @param string|DateTime  $now  A string or DateTime object to compare with. If none given, the current time will be used.
     * @return string the converted time
     */
    public function echeanceDiff(Twig_Environment $env ,$dateDebut, $dateFin = null)
    {
        $interval ='';
        if(null != $dateDebut){
            $dateFin = twig_date_converter($env, $dateFin);
            $diff = $dateDebut->diff($dateFin);
            $interval = $diff->d;
            if($diff->invert == 0 ){
                $interval =  -1 * $diff->d;
            }
        }
        return $interval;
    }

    /**
     * @param string $email
     * @return mixed|string
     */
    public function extraitDomainMail(string $email){

        return explode('.', substr(strstr($email, '@'), 1))[0];
    }

    /**
     * @param array $roles
     * @param string $menu
     * @return bool
     * gestion des  acceès menu par different profils
     */
    public function accesWithHabMenu(array $roles, string $menu){
        $acces = false;
        foreach ($roles as $role){
                $rolesAcces = [
                    "Appel_a_projets" => ["ROLE_ADMIN","ROLE_DOS_EM"],
                    // Roles accès au menu comite
                    "comites" => ["ROLE_ADMIN","ROLE_DOS_EM", "ROLE_CPS_P","ROLE_CPS_S","ROLE_PILOTE"] ,
                    // Roles accès au menu ces
                    "ces" => ["ROLE_ADMIN","ROLE_DOS_EM","ROLE_CPS_P","ROLE_CPS_S","ROLE_PILOTE","ROLE_VISE_PRES","ROLE_PRES"]
                ];
                if(in_array($role->getRole(), $rolesAcces[$menu])){
                    $acces = true;
                    break;
                }
            }
        return $acces;
    }

    public function NonDefinedVar(object $var){
        return isset($var) ? $var : '';
    }

    /**
     * @param ArrayIterator|ArrayCollection|array $evaluations
     * @return TgAffectation[]
     * tri un tableau d'évaluation selon l'ordre de son status
     */
    public function sortEvaluations($evaluations) {
        if(!empty($evaluations)) {
            if($evaluations instanceof FilterIterator) {
                $evaluations->uasort(function ($e1, $e2) {            
                    $order1 = $this->getEvaluationStatusOrder($e1);
                    $order2 = $this->getEvaluationStatusOrder($e2);
                    
                    if ($order1 == $order2) return 0;
                    return $order1 < $order2 ? -1 : 1;
                });
            } else if($evaluations instanceof ArrayCollection) {
                $iterator = $evaluations->getIterator();
                $iterator->uasort(function ($e1, $e2) {
                    $order1 = $this->getEvaluationStatusOrder($e1);
                    $order2 = $this->getEvaluationStatusOrder($e2);

                    if ($order1 == $order2) return 0;
                    return $order1 < $order2 ? -1 : 1;
                });
                $evaluations = new ArrayCollection(iterator_to_array($iterator));
            } else if(is_array($evaluations)) {
                usort($evaluations, function ($e1, $e2) {
                    $order1 = $this->getEvaluationStatusOrder($e1);
                    $order2 = $this->getEvaluationStatusOrder($e2);

                    if ($order1 == $order2) return 0;
                    return $order1 < $order2 ? -1 : 1;
                });
            }
            return $evaluations;
        }
        
        return $evaluations;
    }

    
    /**
     * retourne la valeur de l'ordre du statut prévalent d'une ligne d'évaluation
     */
    private function getEvaluationStatusOrder(TgAffectation $evaluation) {
        $sts = $evaluation->getCdStsEvaluation();
        $sol = $evaluation->getCdSollicitation();

        if(!empty($sts)) {
            $osts = $sts->getTlStsEvaluation()->getOrdreAffichage();
            if(!empty($sol)) {
                $osol = $sol->getTlStsEvaluation()->getOrdreAffichage();
                //sts_evaluation.ordre_affichage != null alors en priorité
                if($osts != null)
                    return $osts;
                
                return $osts > $osol ? ($osts != null ? $osts : 0) : ($osol != null ? $osol : 0);
            } else {
                return $osts;
            }
        } else {
            if(!empty($sol)) {
                return $sol->getTlStsEvaluation()->getOrdreAffichage();
            } else {
                return -1;
            }
        }        
    }

}