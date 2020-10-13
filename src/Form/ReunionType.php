<?php

namespace App\Form;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgPhase;
use App\Entity\TgReunion;


use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Constraint\IsFalse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReunionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $appel = $options['appel'];

            $builder
                ->add('idAppel', EntityType::class, array(
                        'class' => TgAppelProj::class,
                        'query_builder' => function (EntityRepository $er) use ($appel) {
                            $result = $er->createQueryBuilder('u')
                                ->where('u.idAppel = :appel')
                                ->setParameter('appel', $appel);
                            return $result;
                        },
                        'label' => 'reunion.form.comite',
                        'attr'=>['class' => 'hidden']
                    )
                )
            ->add('idPhase', EntityType::class, [
                'class'=>TgPhase::class,
                'query_builder' => function (EntityRepository $er) use ($appel) {
                    $result = $er->createQueryBuilder('u')
                        ->join('u.idNiveauPhase', 'n')
                        ->where('n.idAppel = :appel')
                        ->setParameter('appel', $appel);

                    return $result;
                 },
                'translation_domain' => 'Reunion',
                'label' => 'Phase',

                ])
            ->add('lbTitre',TextType::class,[
                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.titre',
                'attr' => ['class' => 'form-controle','maxlength' => 50]

            ])

            ->add('idTypeReunion', null, [
                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.typereunion',
                  'required' => true,

            ])
            ->add('txComment', TextareaType::class,[
                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.comment',
            ])
            ->add('dtDebPeriode', DateType::Class, [
                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.dtdebreunion',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyy',
                'html5' => false, // désactiver l'affichage du calendrier Date html 5 on utilise datapicker
                'attr' => ['autocomplete' => 'off', 'class' => 'readonly', 'placeholder' => 'dd/mm/yyyy'],
                'required' => true
            ])
            ->add('dtFinPeriode', DateType::Class, [

                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.dtfinreunion',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyy',
                'html5' => false, // désactiver l'affichage du calendrier Date html 5 on utilise datapicker
                'attr' => ['autocomplete' => 'off', 'class' => 'readonly', 'placeholder' => 'dd/mm/yyyy'],
                'required' => true

            ])
            ->add('nbDureeMax',IntegerType::class,[
                'translation_domain' => 'Reunion',
                'label' => 'reunion.form.dureemax',
                'attr' => ['min' => 1],
                'help' => 'En jours',

            ])
            ->add('blObligatoire',null,[
                'translation_domain' => 'Reunion',
                'label' => false,
                'data'=> true
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgReunion::class,
            'appel' => null
        ]);
//        $resolver->setRequired([
//            'postComite'
//        ]);
    }
}