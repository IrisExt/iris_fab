<?php

namespace App\Form;


use App\Entity\TgAdrMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgAdrMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adrMail', EmailType::class,[
                'label' => 'Adresse mail',
                'required' => true
            ])
            ->add('blNotification', null,[
                'label' => 'Notification'
            ]);
//            ->add('blValide', null,[
//                'label' => 'Valide'
//            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgAdrMail::class,
        ]);
    }
}
