<?php


namespace App\Form\Blocs\Type;

use App\Entity\TgProjet;
use App\Entity\TrGenre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlPartenairePufType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rnsr_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.rnsr'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['rnsr_puf'], 'minlength' => 10, 'maxlength' => 20],
                'disabled' => $options['op_disabled']['rnsr_puf']
            ])
            ->add('siret_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_heb'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['siret_tut_heb_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['siret_tut_heb_puf']
            ])
            ->add('name_tut_heb_puf', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_heb'),
                'required' => true,
                'attr' => ['class' => 'js-states hidden_'.$options['hidden']['name_tut_heb_puf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['name_tut_heb_puf']
            ])
            ->add('delegation_tut_heb_puf', ChoiceType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.delegation_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'js-states hidden_'.$options['hidden']['delegation_tut_heb_puf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['delegation_tut_heb_puf']
            ])
//            ->add('name_tut_heb_puf', TextType::class, [
//                'mapped' => false,
//                'translation_domain' => 'Blocs',
//                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_heb'),
//                'required' => false,
//                'attr' => ['class' => 'hidden_'.$options['hidden']['name_tut_heb_puf']],
//                'disabled' => $options['op_disabled']['name_tut_heb_puf']
//            ])
            ->add('laboratoire_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.laboratoire'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['laboratoire_puf'], 'maxlength' => 155],
                'disabled' => $options['op_disabled']['laboratoire_puf']
            ])
            ->add('code_unite_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.code_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['code_unite_puf'], 'maxlength' => 10],
                'disabled' => $options['op_disabled']['code_unite_puf']
            ])
            ->add('adress_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['adress_tut_heb_puf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['adress_tut_heb_puf']
            ])
            ->add('compl_adress_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['compl_adress_tut_heb_puf'], 'maxlength' => 100],
                'disabled' => $options['op_disabled']['compl_adress_tut_heb_puf']
            ])
            ->add('postal_code_tut_heb_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_heb'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['postal_code_tut_heb_puf'], 'maxlength' => 10],
                'disabled' => $options['op_disabled']['postal_code_tut_heb_puf']
            ])
            ->add('country_tut_hub_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_heb'),
                'required' => true,
                'empty_data' => 'France',
                'attr' => ['class' => ' hidden_'.$options['hidden']['country_tut_hub_puf'], 'placeHolder' => 'France'],
                'disabled' => $options['op_disabled']['country_tut_hub_puf']
            ])
            ->add('city_tut_hub_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_heb'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['city_tut_hub_puf'], 'maxlength' => 100],
                'disabled' => $options['op_disabled']['city_tut_hub_puf']
            ])
            ->add('firstname_direct_lab_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_direct_lab_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['firstname_direct_lab_puf']
            ])
            ->add('lastname_direct_lab_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_direct_lab_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lastname_direct_lab_puf']
            ])
            ->add('courriel_direct_lab_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.courriel_direct_lab'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['courriel_direct_lab_puf'],  'maxlength' => 40],
                'disabled' => $options['op_disabled']['courriel_direct_lab_puf']
            ])

            ->add('siret_tut_gest_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.siret_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['siret_tut_gest_puf'],  'maxlength' => 50],
                'disabled' => $options['op_disabled']['siret_tut_gest_puf']
            ])
            ->add('name_tut_gest_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['name_tut_gest_puf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['name_tut_gest_puf']
            ])
            ->add('sigle_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.sigle'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['sigle_puf'], 'maxlength' => 40],
                'disabled' => $options['op_disabled']['sigle_puf']
            ])
            ->add('adress_tut_gest_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['adress_tut_gest_puf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['adress_tut_gest_puf']
            ])
            ->add('compl_adress_tut_gest_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['compl_adress_tut_gest_puf'], 'maxlength' => 100],
                'disabled' => $options['op_disabled']['compl_adress_tut_gest_puf']
            ])
            ->add('postal_code_tut_gest_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['postal_code_tut_gest_puf'], 'maxlength' => 10],
                'disabled' => $options['op_disabled']['postal_code_tut_gest_puf']
            ])
            ->add('city_tut_g_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_gest'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['city_tut_g_puf'], 'maxlength' => 100],
                'disabled' => $options['op_disabled']['city_tut_g_puf']
            ])
            ->add('country_tut_g_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_gest'),
                'required' => false,
                'empty_data' => 'France',
                'attr' => ['class' => ' hidden_'.$options['hidden']['country_tut_g_puf'], 'placeHolder' => 'France'],
                'disabled' => $options['op_disabled']['country_tut_g_puf']
            ])
            ->add('banque_tut_g_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.banque_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['banque_tut_g_puf'], 'maxlength' => 40],
                'disabled' => $options['op_disabled']['banque_tut_g_puf']
            ])
            ->add('rib_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.rib_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['rib_puf'], 'minlength' => 23, 'maxlength' => 23],
                'disabled' => $options['op_disabled']['rib_puf']
            ])
            ->add('iban_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.iban_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['iban_puf'], 'minlength' => 18, 'maxlength' => 30],
                'disabled' => $options['op_disabled']['iban_puf']
            ])
            ->add('firstname_rep_juridique_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_rep_juridique_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['firstname_rep_juridique_puf']
            ])
            ->add('lastname_rep_juridique_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_rep_juridique_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lastname_rep_juridique_puf']
            ])
            ->add('function_rep_juridique_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.function_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['function_rep_juridique_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['function_rep_juridique_puf']
            ])


//            ->add('gender_gest_admin_puf', EntityType::class, [
//                'class' => TrGenre::class,
//                'mapped' => false,
//                'label' => false,
//                'required'=>false,
//                'expanded'=>true,
//                'multiple'=>false,
//                'placeholder'=>false,
//                'translation_domain' => 'Blocs',
//                'attr' => ['class' => 'gender hidden_'.$options['hidden']['gender_gest_admin_puf']],
//                'disabled' => $options['op_disabled']['gender_gest_admin_puf']
//            ])
            ->add('firstname_gest_admin_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_gest_admin_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['firstname_gest_admin_puf']
            ])
            ->add('lastname_gest_admin_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_gest_admin_puf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lastname_gest_admin_puf']
            ])
            ->add('mail_gest_admin_puf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.mail_gest_admin'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['mail_gest_admin_puf'], 'maxlength' => 40],
                'disabled' => $options['op_disabled']['mail_gest_admin_puf']
            ])
//            ->add('phone_gest_admin_puf', TextType::class, [
//                'mapped' => false,
//                'translation_domain' => 'Blocs',
//                'label' => $this->translator->trans('bloc.form.blpartenariat.phone_gest_admin'),
//                'required' => false,
//                'attr' => ['class' => 'hidden_'.$options['hidden']['phone_gest_admin_puf']],
//                'disabled' => $options['op_disabled']['phone_gest_admin_puf']
//            ])
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
        return 'BlPartenairePufType';
    }

}