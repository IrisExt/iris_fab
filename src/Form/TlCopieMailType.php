<?php

namespace App\Form;


use App\Entity\TgAdrMail;
use App\Entity\TlCopieMail;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TlCopieMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('blCache')
//            ->add('idCourriel')
//            ->add('adrMail', EntityType::class,[
//                'class'=> TgAdrMail::class,
//                'label' => 'Copie'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TlCopieMail::class,
        ]);
    }
}
