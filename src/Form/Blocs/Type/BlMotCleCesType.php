<?php


namespace App\Form\Blocs\Type;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgMcCes;
use App\Entity\TgProjet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlMotCleCesType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BlMotCleCesType constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }
    protected function addElements(FormInterface $form, $idComite = null, $options)
    {
        $mcCes = array();

        if ($idComite) {
            $mcCes = $this->em->getRepository(TgMcCes::class)->findBy(['idComite' => $idComite]);

            $form
                ->add('idMcCes', EntityType::class, [
                    'class' => TgMcCes::class,
                    'translation_domain' => 'Blocs',
                    'label' => false,
                    'required' => false,
                    'multiple' => true,
                    'attr' => ['class' => 'dual_select hidden_'.$options['hidden']['idMcCes']],
                    'disabled' => $options['op_disabled']['idMcCes'],
                    'choices' => $mcCes
                ]);

        } else {

            $form
                ->add('idMcCes', EntityType::class, [
                    'class' => TgMcCes::class,
                    'translation_domain' => 'Blocs',
                    'label' => false,
                    'required' => false,
                    'multiple' => true,
                    'attr' => ['class' => 'dual_select hidden_1'],
                    'disabled' => true,
                    'choices' => $mcCes
                ]);
        }



    }

    function onPreSetData(FormEvent $event)
    {
        $tgProjet = $event->getData();
        $form = $event->getForm();
        $options = $event->getForm()->getConfig()->getOptions();
        $idComite =  $tgProjet->getIdComite()?: null;
        $this->addElements($form, $idComite, $options);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
            'op_readonly' =>  ['readonly' => false],
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BlMotCleCesType';
    }
    
}