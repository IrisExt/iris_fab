<?php


namespace App\Form\Blocs\Type;

use App\Entity\TgNonSouhaite;
use App\Entity\TgProjet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NonSouhaiteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blnonsouh.nom',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['nom'], 'maxlength' => 50, 'minlength' => 2],
                'disabled' => $options['op_disabled']['nom']
            ])
            ->add('prenom', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blnonsouh.prenom',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['prenom'], 'maxlength' => 50, 'minlength' => 2],
                'disabled' => $options['op_disabled']['prenom']
            ])
            ->add('courriel', EmailType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blnonsouh.courriel',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['courriel'], 'maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Mettre une adresse mail valide'],
                'disabled' => $options['op_disabled']['courriel']
            ])
            ->add('organisme', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blnonsouh.organisme',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['organisme'], 'maxlength' => 50, 'minlength' => 2],
                'disabled' => $options['op_disabled']['organisme']
            ])
            ->add('lbMotif', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blnonsouh.motif',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbMotif'], 'maxlength' => 50, 'minlength' => 2],
                'disabled' => $options['op_disabled']['lbMotif']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
            'op_readonly' =>  ['readonly' => false],
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'NonSouhaiteType';
    }
}