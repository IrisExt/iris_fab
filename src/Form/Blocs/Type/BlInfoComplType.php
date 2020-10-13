<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrCoFi;
use App\Entity\TrInfRech;
use App\Entity\TrPolComp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BlInfoComplType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blDemLabel', ChoiceType::class, [
                'choices' => ['Oui' => true, 'Non' => false],
                'label' => false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'placeholder'=>false,
                'translation_domain' => 'Blocs',
                'attr' => ['class' => 'hidden_'.$options['hidden']['blDemLabel']],
                'disabled' => $options['op_disabled']['blDemLabel']
            ])
            ->add('idPoleComp', EntityType::class, [
                'class' => TrPolComp::class,
                'translation_domain' => 'Blocs',
                'label' => false,
                'required' => false,
                'multiple' => true,
                'attr' => array('class' => 'dual_select hidden_'.$options['hidden']['idPoleComp'], 'size' => '10'),
                'label_attr' => ['class' => 'hideDem'],
                'disabled' => $options['op_disabled']['idPoleComp']
            ])
            ->add('blInfraRecherche', ChoiceType::class, [
                'choices' => ['Oui' => true, 'Non' => false],
                'translation_domain' => 'Blocs',
                'label' => false,
                'required' => false,
                'expanded'=>true,
                'multiple'=>false,
                'placeholder'=>false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['blInfraRecherche']],
                'disabled' => $options['op_disabled']['blInfraRecherche']
            ])
            ->add('idInfRech', EntityType::class, [
                'class' => TrInfRech::class,
                'translation_domain' => 'Blocs',
                'label' => false,
                'required' => false,
                'multiple' => true,
                'attr' => array('class' => 'dual_select hidden_'.$options['hidden']['idInfRech'], 'size' => '10'),
                'label_attr' => ['class' => 'hideDem'],
                'disabled' => $options['op_disabled']['idInfRech']
            ])
            ->add('blDemCofi', ChoiceType::class, [
                'choices' => ['Oui' => true, 'Non' => false],
                'translation_domain' => 'Blocs',
                'label' => false,
                'required' => false,
                'expanded'=>true,
                'multiple'=>false,
                'placeholder'=>false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['blDemCofi']],
                'disabled' => $options['op_disabled']['blDemCofi']
            ])
            ->add('idCoFi', EntityType::class, [
                'class' => TrCoFi::class,
                'translation_domain' => 'Blocs',
                'label' => false,
                'multiple' => true,
                'required' => false,
                'attr' => array('class' => 'dual_select hidden_'.$options['hidden']['idCoFi'], 'size' => '10'),
                'label_attr' => ['class' => 'hideDem'],
                'disabled' => $options['op_disabled']['idCoFi']
            ]);
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
        return 'BlInfoComplType';
    }

}