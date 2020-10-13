<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BlRespScientifiqueType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname_direct_lab', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.firstname_resp_sc'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['firstname_direct_lab']],
                'disabled' => $options['op_disabled']['firstname_direct_lab']
            ])
            ->add('lastname_direct_lab', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.lastname_resp_sc'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lastname_direct_lab']],
                'disabled' => $options['op_disabled']['lastname_direct_lab']
            ])
            ->add('coord_francais', CheckboxType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.coord_francais'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['coord_francais']],
                'disabled' => $options['op_disabled']['coord_francais']
            ])
            ->add('coord_etranger', CheckboxType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => $this->translator->trans('bloc.form.blpartenariat.coord_etranger'),
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['coord_etranger']],
                'disabled' => $options['op_disabled']['coord_etranger']
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
        return 'BlRespScientifiqueType';
    }

}