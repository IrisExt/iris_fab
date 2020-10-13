<?php


namespace App\Form\Blocs\Type;

use App\Entity\TrAgFi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TrAgFiType
 * @package App\Form\Blocs\Type
 */
class TrAgFiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idAgenceFi', EntityType::class, [
            'class' => TrAgFi::class,
            'translation_domain' => 'Blocs',
            'label' => 'bloc.form.blinstfi.cooperation',
            'label_attr' => ['class' => 'PRCI'],
            'required' => false,
            'attr' => ['class' => 'chosen-select PRCI'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>  TrAgFi::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'TrAgFiType';
    }

}