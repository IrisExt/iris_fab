<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlTutHebType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rnsr', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.rnsr'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['rnsr']],
                'disabled' => $options['op_disabled']['rnsr']
            ])
            ->add('siret', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['siret']],
                'disabled' => $options['op_disabled']['siret']
            ])
            ->add('name_tut_heb', EmailType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['name_tut_heb']],
                'disabled' => $options['op_disabled']['name_tut_heb']
            ])
            ->add('adress_tut_heb', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['adress_tut_heb']],
                'disabled' => $options['op_disabled']['adress_tut_heb']
            ])
            ->add('compl_adress_tut_heb', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['compl_adress_tut_heb']],
                'disabled' => $options['op_disabled']['compl_adress_tut_heb']
            ])
            ->add('postal_code_tut_heb', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['postal_code_tut_heb']],
                'disabled' => $options['op_disabled']['postal_code_tut_heb']
            ])
            ->add('country_tut_heb', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['country_tut_heb']],
                'disabled' => $options['op_disabled']['country_tut_heb']
            ])
            ->add('city_tut_heb', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['city_tut_heb']],
                'disabled' => $options['op_disabled']['city_tut_heb']
            ])
            ->add('firstname_direct_lab', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_direct_lab']],
                'disabled' => $options['op_disabled']['firstname_direct_lab']
            ])
            ->add('lastname_direct_lab', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_direct_lab']],
                'disabled' => $options['op_disabled']['lastname_direct_lab']
            ])
            ->add('courriel_direct_lab', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.courriel_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['courriel_direct_lab']],
                'disabled' => $options['op_disabled']['courriel_direct_lab']
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
        return 'BlTutHebType';
    }

}