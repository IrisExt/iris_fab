<?php


namespace App\Form\CvBlocs;


use App\Entity\TgProjet;

use App\Entity\TrChoixDispoExpert;
use App\Entity\TrDispoComite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DisponibleType extends AbstractType
{

    function buildForm(FormBuilderInterface $builder, array $options)
    {

        $dispoCmte = $options['dispoComite'];
        $dispoExp = $options['dispoExp'];

        $builder
            ->add('choixDispoExp', EntityType::class, [
                'class' => TrChoixDispoExpert::class,
                'data' => $dispoExp ?? '',
                'placeholder' => 'Choisir diponibilité',
                'label' => false,
                'attr' => ['class' => 'chosen-select'],
                'required' => false
            ])

        ->add('choixComite', EntityType::class, [
            'class' => TrDispoComite::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nbOrdre', 'ASC');
            },
            'data' => $dispoCmte ?? '',
            'placeholder' => 'Merci d\'indiquer votre choix',
            'label' => false,
             'attr' => ['class' => 'chosen-select'],
              'required' => false
    ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => TrChoixDispoExpert::class,
              'dispoComite' => null,
              'dispoExp' => null
        ]);
    }

}