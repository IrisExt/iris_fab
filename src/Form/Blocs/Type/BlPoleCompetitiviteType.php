<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrPolComp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BlPoleCompetitiviteType extends AbstractType
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
        return 'BlPoleCompetitiviteType';
    }

}