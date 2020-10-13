<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlMotCleLibreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbNomFr',  TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.mcle.fr',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbNomFr'], 'maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Mettre un terme en français'],
                'disabled' => $options['op_disabled']['lbNomFr']
            ])
            ->add('lbNomEn',  TextType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.mcle.en',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbNomEn'], 'maxlength' => 50, 'minlength' => 2, 'placeholder' => 'Mettre un terme en englais'],
                'disabled' => $options['op_disabled']['lbNomEn']
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
        return 'BlMotCleLibreType';
    }

}