<?php

namespace App\Form\CvBlocs;


use App\Entity\TgOrganisme;
use App\Entity\TrPays;
use App\Form\TgAdresseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgOrganismeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idOrganisme', HiddenType::class)

            ->add('lbNomFr',TextType::class,[
                'label' => 'Etablissement',
                'required' => true,
        ])
            ->add('lbLaboratoire', TextType::class,[
                'label' => 'Laboratoire',

        ])
            ->add('ville', TextType::class,[
                'label' => 'Ville',
            ])
                ->add('cdPays', EntityType::class,[
                    'class'=> TrPays::class,
                    'label' => 'Pays'
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgOrganisme::class,
        ]);
    }
}
