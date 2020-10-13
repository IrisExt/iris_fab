<?php

namespace App\Form;

use App\Entity\TgAdresse;
use App\Entity\TrPays;
use App\Form\CvBlocs\TgOrganismeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgAdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville')
            ->add('cdPays',EntityType::class, [
                'class' => TrPays::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lbPays', 'ASC');
                },
                'choice_label' => 'lbPays',
                'attr' => ['class' => 'js-states' , 'style' => "width: 100%"],
                'label' => 'Pays',
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgAdresse::class,
        ]);
    }
}
