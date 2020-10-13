<?php


namespace App\Form\CvBlocs;


use App\Entity\TgPublication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgPublicationType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('lbTitre', TextareaType::class,[
                'label' => false
                ])
            ->add('lbJustification',TextareaType::class,[
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgPublication::class,

        ]);
    }
}