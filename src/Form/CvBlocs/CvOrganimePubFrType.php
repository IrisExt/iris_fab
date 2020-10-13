<?php


namespace App\Form\CvBlocs;


use App\Entity\TgCv;
use App\Entity\TgOrganisme;
use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class CvOrganimePubFrType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idOrganisme', HiddenType::class)

            ->add('rnsr_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.rnsr'),
                'required' => true,
            ])
            ->add('name_tut_heb_puf', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_heb'),
                'required' => true,
                'attr' => ['class' => 'js-states','style'=>"width: 100%"],
            ])
//            ->add('delegation_tut_heb_puf', ChoiceType::class, [
//                'mapped' => false,
//                'translation_domain' => 'Blocs',
//                'label' => $this->translator->trans('bloc.form.blpartenariat.delegation_tut_heb'),
//                'required' => false,
//                'attr' => ['class' => 'js-states'],
//            ])

            ->add('siret_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_heb'),
                'required' => false,
                'disabled' => true
            ])
            ->add('laboratoire_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.laboratoire'),
                'required' => false,
                'disabled' => true
            ])
            ->add('code_unite_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.code_tut_heb'),
                'required' => false,
                'disabled' => true
            ])

            ->add('adress_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_heb'),
                'required' => false,
                'disabled' => true
            ])
            ->add('compl_adress_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_heb'),
                'required' => false,
                'disabled' => true
            ])
            ->add('postal_code_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_heb'),
                'required' => false,
                'disabled' => true
            ])
            ->add('country_tut_hub_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_heb'),
                'required' => false,
                'empty_data' => 'France',
                'attr' => ['placeHolder' => 'France'],
                'disabled' => true
            ])
            ->add('city_tut_hub_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_heb'),
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
        return 'BlPartenairePufType';
    }

}