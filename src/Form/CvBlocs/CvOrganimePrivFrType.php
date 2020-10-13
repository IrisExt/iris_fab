<?php


namespace App\Form\CvBlocs;



use App\Entity\TgOrganisme;

use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class CvOrganimePrivFrType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_gest'),
                'required' => true,
                'attr' => ['maxlength' => 14 , 'minlength' => 14, 'placeholder' => 'xxxxxxxxxxxxxx'],
//                'disabled' => $options['op_disabled']['siret_tut_gest_prf']
            ])
            ->add('service', TextType::class, [
                'mapped' => false,
                'label' => 'Direction service',
                'required' => false,
            ])
            ->add('name_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_gest'),
                'required' => false,
                'disabled' => true
            ])
            ->add('sigle_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.sigle'),
                'required' => false,
                'disabled' => true
            ])
            ->add('adress_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_gest'),
                'required' => false,
                'disabled' => true
            ])
            ->add('compl_adress_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_gest'),
                'required' => false,
                'disabled' => true
            ])
            ->add('postal_code_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_gest'),
                'required' => false,
                'disabled' => true
            ])
            ->add('city_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_gest'),
                'required' => false,
                'disabled' => true
            ])
            ->add('country_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_gest'),
                'required' => false,
                'disabled' => true
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgOrganisme::class,
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BlPartenairePrfType';
    }

}