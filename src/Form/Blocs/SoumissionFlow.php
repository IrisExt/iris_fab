<?php


namespace App\Form\Blocs;

use App\Entity\TgFormulaire;
use Craue\FormFlowBundle\Form\FormFlow;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SoumissionFlow
 * @package App\Form\Blocs
 */
class SoumissionFlow extends FormFlow
{

    /**
     * @var bool
     */
    protected $allowDynamicStepNavigation = true;
    protected $em;
    protected $request;
    protected $idFormulaire;

    /**
     * SoumissionFlow constructor.
     * @param EntityManager $em
     * @param RequestStack $request_stack
     */
    public function __construct(EntityManager $em, RequestStack $request_stack)
    {
        $this->em = $em;
        $this->request = $request_stack->getCurrentRequest();
        $this->idFormulaire = $this->request->get('id_formulaire');
    }

    /**
     * @return array
     */
    public function loadStepsConfig()
    {
        $formulaire = $this->em->getRepository(TgFormulaire::class)->findOneBy(array('idFormulaire' => $this->idFormulaire));

        $return = array();
        $i = 0;
        foreach ($formulaire->getTlBlocForm()->getValues() as $blocForm) {
            $bloc = $blocForm->getIdBloc();
            $return[$i]['label'] = $bloc->getLbBloc();
            $class_name = $bloc->getClassName();
            if (is_null($class_name)) {
                $return[$i]['skip'] = true;
            } else {
                $return[$i]['form_type'] = 'App\Form\Blocs\Type\\' . $bloc->getClassName();
            }
            $i++;
        }
        return $return;
    }

}