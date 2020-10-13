<?php


namespace App\Form\CvBlocs;


use App\Entity\TlCvPubl;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TlCvPublType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('idPublication', CollectionType::class,[
                'entry_type' =>  TgPublicationType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'attr' => ['maxlength' => 255],

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => TlCvPubl::class,

        ]);
    }

}