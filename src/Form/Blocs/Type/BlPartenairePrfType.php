<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrGenre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlPartenairePrfType extends AbstractType
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
                'attr' => ['class' => 'hidden_'.$options['hidden']['siret_tut_gest_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['siret_tut_gest_prf']
            ])
            ->add('name_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_tut_gest'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['name_tut_gest_prf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['name_tut_gest_prf']
            ])
            ->add('sigle_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.sigle'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['sigle_prf'], 'maxlength' => 40],
                'disabled' => $options['op_disabled']['sigle_prf']
            ])
            ->add('adress_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['adress_tut_gest_prf'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['adress_tut_gest_prf']
            ])
            ->add('compl_adress_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.compl_adress_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['compl_adress_tut_gest_prf'],  'maxlength' => 100],
                'disabled' => $options['op_disabled']['compl_adress_tut_gest_prf']
            ])
            ->add('postal_code_tut_gest_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.postal_code_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['postal_code_tut_gest_prf'],  'maxlength' => 10],
                'disabled' => $options['op_disabled']['postal_code_tut_gest_prf']
            ])
            ->add('city_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_gest'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['city_tut_g_prf'],  'maxlength' => 100],
                'disabled' => $options['op_disabled']['city_tut_g_prf']
            ])
            ->add('country_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_gest'),
                'required' => false,
                'empty_data' => 'France',
                'attr' => ['class' => ' hidden_'.$options['hidden']['country_tut_g_prf'], 'placeHolder' => 'France'],
                'disabled' => $options['op_disabled']['country_tut_g_prf']
            ])
            ->add('banque_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.banque_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['banque_tut_g_prf'],  'maxlength' => 40],
                'disabled' => $options['op_disabled']['banque_tut_g_prf']
            ])
            ->add('rib_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.rib_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['rib_tut_g_prf'], 'minlength' => 23, 'maxlength' => 23],
                'disabled' => $options['op_disabled']['rib_tut_g_prf']
            ])
            ->add('iban_tut_g_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.iban_tut_g'),
                'required' => false,
                'attr' => ['class' => ' hidden_'.$options['hidden']['iban_tut_g_prf'], 'minlength' => 18, 'maxlength' => 30],
                'disabled' => $options['op_disabled']['iban_tut_g_prf']
            ])
            ->add('firstname_rep_juridique_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_rep_juridique_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['firstname_rep_juridique_prf']
            ])
            ->add('lastname_rep_juridique_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_rep_juridique_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lastname_rep_juridique_prf']
            ])
            ->add('function_rep_juridique_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.function_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['function_rep_juridique_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['function_rep_juridique_prf']
            ])
//            ->add('gender_gest_admin_prf', EntityType::class, [
//                'class' => TrGenre::class,
//                'mapped' => false,
//                'label' => false,
//                'required'=>false,
//                'expanded'=>true,
//                'multiple'=>false,
//                'placeholder'=>false,
//                'translation_domain' => 'Blocs',
//                'attr' => ['class' => 'gender hidden_'.$options['hidden']['gender_gest_admin_prf']],
//                'disabled' => $options['op_disabled']['gender_gest_admin_prf']
//            ])
            ->add('firstname_gest_admin_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_gest_admin'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_gest_admin_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['firstname_gest_admin_prf']
            ])
            ->add('lastname_gest_admin_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_gest_admin'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_gest_admin_prf'], 'maxlength' => 50],
                'disabled' => $options['op_disabled']['lastname_gest_admin_prf']
            ])
            ->add('mail_gest_admin_prf', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.mail_gest_admin'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['mail_gest_admin_prf'], 'maxlength' => 40],
                'disabled' => $options['op_disabled']['mail_gest_admin_prf']
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
        return 'BlPartenairePrfType';
    }

}