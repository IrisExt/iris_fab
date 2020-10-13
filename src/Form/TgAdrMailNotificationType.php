<?php


namespace App\Form;


use App\Entity\TgAdrMailNotification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgAdrMailNotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adrMailNotif', EmailType::class,[
               'label' => 'Adresse mail'
            ])
            ->add('blObsolete', null,[
            'label' => 'Obsolète'
      ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgAdrMailNotification::class,
        ]);
    }

}