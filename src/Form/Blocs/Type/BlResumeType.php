<?php


namespace App\Form\Blocs\Type;



use App\Entity\TgProjet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlResumeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('resumeFr', TextareaType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.resume.fr',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['resumeFr']],
                'disabled' => $options['op_disabled']['resumeFr']
            ])
            ->add('resumeEn', TextareaType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.resume.en',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['resumeEn']],
                'disabled' => $options['op_disabled']['resumeEn']
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
        return 'BlResumeType';
    }

}