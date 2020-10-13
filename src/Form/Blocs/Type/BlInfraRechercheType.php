<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrInfRech;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlInfraRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'attr' => ['class' => 'dual_select'],
                'multiple' => true,
                'required' => false,
                'label_attr' => ['class' => 'hideDem'],
                'attr' => ['class' => 'hidden_'.$options['hidden']['idInfRech'], 'size' => '10'],
                'disabled' => $options['op_disabled']['idInfRech']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BlInfraRechercheType';
    }

}