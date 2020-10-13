<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgProjet;
use App\Entity\TrCatRd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

class BlIdentProjType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BlIdentProjType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbAcro', TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.acronyme',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbAcro'],'minlength' => 3, 'maxlength' => 40 ],
                'disabled' => $options['op_disabled']['lbAcro']
            ])
            ->add('lbTitreFr', TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.titre_fr',
                'required' => false,
               'attr' => ['class' => 'hidden_'.$options['hidden']['lbTitreFr'], 'minlength' => 2, 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lbTitreFr']
            ])
            ->add('lbTitreEn', TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.titre_en',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbTitreEn'], 'minlength' => 2, 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lbTitreEn']
            ])
            ->add('noDuree', ChoiceType::class, [
                'choices' => ['24 mois' => '24', '30 mois' => '30', '36 mois' => '36', '42 mois' => '42', '48 mois' => '48'],
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.duree',
                'required' => false,
                'attr' => ['class' => 'chosen-select hidden_'.$options['hidden']['noDuree']],
                'disabled' => $options['op_disabled']['noDuree']
            ])
            ->add('idCatRd', EntityType::class, [
                'class' => TrCatRd::class,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.type_recherche',
                'required' => false,
                'attr' => ['class' =>'chosen-select hidden_'.$options['hidden']['idCatRd']],
                'disabled' => $options['op_disabled']['idCatRd']
            ])
            ->add('idInfraFi', TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.instrument',
                'attr' => ['readonly' => true, 'class' =>'chosen-select'],
                'disabled' => true,
            ])

            ->add('mntAidePrev', IntegerType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blidentproj.montant_prov',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['mntAidePrev']],
                'disabled' => $options['op_disabled']['mntAidePrev']
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    protected function addElements(FormInterface $form, TgAppelProj $appel = null, $options) {

        $comites = array();

        if ($appel) {
            $comites = $this->em->getRepository(TgComite::class)->findBy(['idAppel' => $appel->getIdAppel()]);
        }

        $form->add('idComite', EntityType::class, [
            'class' => TgComite::class,
            'translation_domain' => 'Blocs',
            'placeholder' => 'bloc.form.blidentproj.comite',
            'label' => 'bloc.form.blidentproj.ces_selectionne',
            'required' => false,
            'attr' => ['class' => 'chosen-select hidden_'.$options['hidden']['idComite']],
            'disabled' => $options['op_disabled']['idComite'],
            'choices' => $comites
        ]);
    }

    function onPreSetData(FormEvent $event)
    {
        $tgProjet = $event->getData();
        $form = $event->getForm();
        $options = $event->getForm()->getConfig()->getOptions();
        $appel =  ($tgProjet)? $tgProjet->getIdAppel() : null;
        $this->addElements($form, $appel, $options);
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
        return 'BlIdentProjType';
    }

}