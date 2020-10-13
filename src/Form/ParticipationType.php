<?php

namespace App\Form;

use App\Entity\TgComite;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TgPhase;
use App\Entity\TrRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbGroupe',TextType::class, ['translation_domain' => 'Participation', 'label' => 'partic.form.Lbgroupe', 'attr' => ['class' => 'form-controle'],])
//            ->add('prioGrp', TextType::class, ['translation_domain' => 'Participation', 'label' => 'partic.form.PrioGrp', 'attr' => ['class' => 'form-controle'],])
//            ->add('dhMaj')
//            ->add('lbRespMaj', TextType::class, ['translation_domain' => 'Participation', 'label' => 'partic.form.LbRespMaj', 'attr' => ['class' => 'form-controle'],])
//            ->add('blSupprime', TextType::class, ['translation_domain' => 'Participation', 'label' => 'partic.form.BlSupprime', 'attr' => ['class' => 'form-controle'],])
            ->add('idRole', EntityType::class, [
                'class' => TrRole::class,
                'translation_domain' => 'Participation',
                'label' => 'partic.form.role',
                'attr' => ['class' => 'chosen-select']
            ])
            ->add('idComite', EntityType::class, [
                'class' => TgComite::class,
                'translation_domain' => 'Participation',
                'label' => 'partic.form.comite',
                'attr' => ['class' => 'chosen-select']
            ])
            ->add('cdEtatSollicitation')
            ->add('idPersonne', EntityType::class, [
                'class' => TgPersonne::class,
                'translation_domain' => 'Participation',
                'label' => 'partic.form.participant',
                'attr' => ['class' => 'chosen-select']
            ])
            ->add('idPhase', EntityType::class, [
                'class' => TgPhase::class,
                'translation_domain' => 'Participation',
                'label' => 'partic.form.phase',
                'attr' => ['class' => 'chosen-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgParticipation::class,
        ]);
    }
}
