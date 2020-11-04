<?php

namespace App\Form;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;


use App\Entity\TgPersonne;
use App\Entity\TrDepartement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ComiteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $appel = $options['appel'];
        $president = $options['president'];
        $cpsPs = $options['cpsPs'];
        $cpsSs = $options['cpsSs'];

        $builder
            ->add('idAppel', EntityType::class,
                [
                    'class' => TgAppelProj::class,
                    'query_builder' => function (EntityRepository $er) use ($appel) {
                        $result = $er->createQueryBuilder('u')
                            ->where('u.idAppel = :appel')
                            ->setParameter('appel', $appel);
                        return $result;
                    },
                    'label' => false,
//                    'translation_domain' => 'Comites',
//                    'label' => 'comite.form.appelprojet',
                    'attr' => ['class' => 'hidden'],
//                    'required' => true
                ])
            ->add('lbAcr', TextType::class, [
                'translation_domain' => 'Comites',
                'label' => 'comite.form.acron',
                'attr' => ['class' => 'form-controle', 'maxlength' => 10],

            ])
            ->add('lbTitre', TextType::class, ['translation_domain' => 'Comites', 'label' => 'comite.form.text', 'attr' => ['class' => 'form-controle', 'maxlength' => 255]])
            ->add('lbDesc', TextareaType::class, ['translation_domain' => 'Comites', 'label' => 'comite.form.desc', 'attr' => ['class' => 'form-controle', 'maxlength' => 1000]])
            ->add('iddepartement', EntityType::class, [
                'class' => TrDepartement::class,
                'multiple' => true,
                'translation_domain' => 'Comites',
                'label' => 'comite.form.departement',
                'attr' => ['class' => 'select2_', 'requierd' => true],

            ])
            ->add('president', Select2EntityType::class, [
                'mapped' => false,
                'class' => TgPersonne::class,
                'remote_route' => 'set_ajax_personne',
                'primary_key' => 'idPersonne',
                'minimum_input_length' => 2,
                'data' => $president ?? null,
                'placeholder' => 'Veuillez choisir un président',
                'translation_domain' => 'Comites',
                'label' => 'comite.form.presi',
                'attr' => ['class' => 'select2_personne'],
                'required' => false,
                'allow_clear' => true,
                'language' => 'Fr'

            ])
            ->add('cpsprincipal', Select2EntityType::class, [
                'mapped' => false,
                'class' => TgPersonne::class,
                'data' => $cpsPs ?? null,
                'multiple' => true,
                'remote_route' => 'set_ajax_personne',
                'primary_key' => 'idPersonne',
                'translation_domain' => 'Comites',
                'label' => 'comite.form.cpsPrin',
                'attr' => ['class' => 'select2_personne'],
                'required' => true
            ])
            ->add('cpsSecondaire', Select2EntityType::class, [
                'mapped' => false,
                'class' => TgPersonne::class,
                'data' => $cpsSs ?? null,
                'translation_domain' => 'Comites',
                'remote_route' => 'set_ajax_personne',
                'primary_key' => 'idPersonne',
                'label' => 'comite.form.cpsSecondaire',
                'multiple' => true,
                'attr' => ['class' => 'select2_personne'],
                'required' => false,
                'allow_clear' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgComite::class,
            'appel' => null,
            'president' => null,
            'cpsPs' => null,
            'cpsSs' => null,
        ]);
    }
}