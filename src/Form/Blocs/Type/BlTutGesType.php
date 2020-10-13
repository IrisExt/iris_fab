<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlTutGesType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['siret']],
                'disabled' => $options['op_disabled']['siret']
            ])
            ->add('name_tut_gest', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['name_tut_gest']],
                'disabled' => $options['op_disabled']['name_tut_gest']
            ])
            ->add('sigle', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.sigle'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['sigle']],
                'disabled' => $options['op_disabled']['sigle']
            ])
            ->add('adress_tut_gest', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['adress_tut_gest']],
                'disabled' => $options['op_disabled']['adress_tut_gest']
            ])
            ->add('compl_adress_tut_gest', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['compl_adress_tut_gest']],
                'disabled' => $options['op_disabled']['compl_adress_tut_gest']
            ])
            ->add('postal_code_tut_gest', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['postal_code_tut_gest']],
                'disabled' => $options['op_disabled']['postal_code_tut_gest']
            ])
            ->add('city_tut_g', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['city_tut_g']],
                'disabled' => $options['op_disabled']['city_tut_g']
            ])
            ->add('country_tut_g', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['country_tut_g']],
                'disabled' => $options['op_disabled']['country_tut_g']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BlTutGesType';
    }

}