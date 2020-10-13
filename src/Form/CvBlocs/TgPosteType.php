<?php


namespace App\Form\CvBlocs;


use App\Entity\TgPoste;
use App\Entity\TrFonction;
use App\Entity\TrPays;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgPosteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
//            ->add('lbAutresActivites', TextareaType::class,[
//                'mapped'=> false,
//                'label' => false
//            ])
            ->add('dtDebut', DateType::class,[
                'widget' => 'single_text',
                'label' => 'Année début',
                'format' => 'yyyy',
                'attr' => ['maxlength' => 4, 'pattern' => "[0-9]{4}", 'placeholder' => 'YYYY'],

            ])
            ->add('dtFin', DateType::class,[
                'widget' => 'single_text',
                'label' => 'Année fin',
                'format' => 'yyyy',
                'attr' => ['maxlength' => 4, 'pattern' => "[0-9]{4}", 'placeholder' => 'YYYY'],

            ])
            ->add('lbNomFr',TextType::class,[
                'mapped' => false,
                'label'=> "Nom de l'organisme",
            ])
            ->add('ville', TextType::class,[
//              'class' => TrPays::class,
              'mapped' => false,
                'label' => 'Ville (Pays)'
             ])

            ->add('idFonction', EntityType::class,[
                'class' => TrFonction::class,
//                'data' => $,
                'label' => 'Fonctions',
//                  'mapped' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgPoste::class,
        ]);
    }
}