<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlGestAdminType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender_gest_admin', ChoiceType::class, [
                'choices' => ['M' => '1', 'F' => '2'],
                'mapped' => false,
                'label' => false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'placeholder'=>false,
                'translation_domain' => 'Blocs',
                'attr' => ['class' => 'gender hidden_'.$options['hidden']['gender_gest_admin']],
                'disabled' => $options['op_disabled']['gender_gest_admin']
            ])
       //     ->add('gender_gest_admin', ChoiceType::class, [
         //       'mapped' => false,
           //     'translation_domain' => 'Blocs',
             //   'label' => $this->translator->trans('bloc.form.blpartenariat.gender_gest_admin'),
               // 'required' => false,
           // ])
            ->add('firstname_gest_admin', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_gest_admin']],
                'disabled' => $options['op_disabled']['firstname_gest_admin']
            ])
            ->add('lastname_gest_admin', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_gest_admin']],
                'disabled' => $options['op_disabled']['lastname_gest_admin']
            ])
            ->add('mail_gest_admin', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.mail_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['mail_gest_admin']],
                'disabled' => $options['op_disabled']['mail_gest_admin']
            ])
            ->add('phone_gest_admin', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.phone_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['phone_gest_admin']],
                'disabled' => $options['op_disabled']['phone_gest_admin']
            ])
        ;

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
        return 'BlGestAdminType';
    }

}