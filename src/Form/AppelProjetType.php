<?php


namespace App\Form;


use App\Entity\TgAppelProj;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dtMillesime',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.edition',
                'attr' => ['maxlength' => 4, 'pattern' => "[0-9]{4}", 'placeholder' => 'YYYY'],
                'required'   => true,
            ])
            ->add('lbAcronyme',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.lbacronyme',
            ])
            ->add('lbAppel',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.titre',
            ])

            ->add('dtCloFin',null,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.dtclofin',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datapicker'
                ]
            ])
            ->add('pilote',null, [
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.pilote',
                'attr' => ['class' => 'chosen-select'],
                ])
            ->add('nbPhase',IntegerType::class, [
                'label' => 'Nombre de Phase',
                'attr' => ['min' => 1, 'max' => 4],
                'help' => 'nombre de phase de l\'appel',

//            ->add('idFormulaire',null, [
//                'translation_domain' => 'AppelProjet',
//                'label' => 'appel.form.formulaire',
//                'attr' => ['class' => 'chosen-select'
//                ],
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgAppelProj::class,
        ]);
    }
}