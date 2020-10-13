<?php

namespace App\Form;

use App\Entity\TgParticipation;
use App\Entity\TrEtatSol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SollicitationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sollicit = $options['sollicit'];

        $builder
            ->add('cdEtatSollicitation', EntityType::class,
                [
                    'class'=> TrEtatSol::class,
                    'label' => 'Sollicitation',
                    'data' => $sollicit->getCdEtatSollicitation(),
//                    'required'=> true
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgParticipation::class,
            'sollicit' => null
        ]);

    }
}