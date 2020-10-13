<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlRepJuridiqueType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname_rep_juridique', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_rep_juridique']],
                'disabled' => $options['op_disabled']['firstname_rep_juridique']
            ])
            ->add('lastname_rep_juridique', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_rep_juridique']],
                'disabled' => $options['op_disabled']['lastname_rep_juridique']
            ])
            ->add('function_rep_juridique', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.function_rep_juridique'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['function_rep_juridique']],
                'disabled' => $options['op_disabled']['function_rep_juridique']
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
        return 'BlRepJuridiqueType';
    }

}