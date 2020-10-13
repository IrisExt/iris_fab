<?php


namespace App\Form;


use App\Entity\TgBloc;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
               ->add('lbbloc', EntityType::class, [
                   'class' => TgBloc::class,
                   'multiple' => true,
                   'attr' => ['size' => 20],
                   'label' => false
                   ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgBloc::class,
        ]);
    }
}