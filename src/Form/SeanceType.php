<?php

namespace App\Form;

use App\Entity\TgComite;
use App\Entity\TgReunion;
use App\Entity\TgSeance;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $reunion = $options['reunion'];
        $comite = $options['comite'];

        $builder
            ->add('idReunion', EntityType::class,[
                'translation_domain' => 'seance',
                'label' => 'seance.form.reunion',
                'class' => TgReunion::class,
                'query_builder' => function (EntityRepository $er) use ($reunion) {
                    return $er->createQueryBuilder('p')
                        ->where('p.idReunion = :idreunion')
                        ->setParameter('idreunion', $reunion);
                }
            ])
            ->add('dtSeance', DateTimeType::class,[
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,    // désactiver l'affichage du calendrier Date html 5 on utilise datapicker
                'attr' => ['autocomplete' => 'off', 'class' => 'readonly', 'placeholder' => 'dd/mm/yyyy'],
                'required' => true
            ])
            ->add('matin',null,[
                'translation_domain' => 'seance',
                'label' => 'seance.form.matin',

            ]

    )
            ->add('apresMidi',null,[
                'translation_domain' => 'seance',
                'label' => 'seance.form.apresmidi',


            ])
            ->add('idComite',EntityType::class,[
                'class' => TgComite::class,
                'label' => false,
                'query_builder' => function (EntityRepository $er) use ($comite) {
                    return $er->createQueryBuilder('p')
                        ->where('p.idComite = :idcomite')
                        ->setParameter('idcomite', $comite);
                },
                'attr'=>['class' => 'hidden']

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgSeance::class,
            'reunion' => null,
            'comite' => null,
            'data_am_pm' => true

        ]);
    }
}
