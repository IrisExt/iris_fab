<?php


namespace App\Twig;

use DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Role\Role;
use Twig\Extension\AbstractExtension;
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

}