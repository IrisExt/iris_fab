<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrPays;
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

class BlPartenaireEtrType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_etr', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.name_etr'),
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['name_etr'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['name_etr']
            ])
            ->add('laboratoire_etr', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.laboratoire'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['laboratoire_etr'], 'maxlength' => 255],
                'disabled' => $options['op_disabled']['laboratoire_etr']
            ])
            ->add('city_etr', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.city_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['city_etr'], 'maxlength' => 100],
                'disabled' => $options['op_disabled']['city_etr']
            ])
            ->add('country_etr', EntityType::class, [
                'class' => TrPays::class,
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.country_tut_gest'),
                'required' => false,
                'attr' => ['class' => 'js-states hidden_'.$options['hidden']['country_etr'], 'placeholder' => 'Choisir un pays'],
                'disabled' => $options['op_disabled']['country_etr']
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
        return 'BlPartenaireEtrType';
    }

}