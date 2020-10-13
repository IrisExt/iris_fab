<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgMcErc;
use App\Entity\TgMotCleErc;
use App\Entity\TgProjet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlMotCleErcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idMcErc', EntityType::class, [
                'class' => TgMotCleErc::class,
                'translation_domain' => 'Blocs',
                'label' => false,
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'dual_select hidden_'.$options['hidden']['idMcErc']],
                'disabled' => $options['op_disabled']['idMcErc']
            ]);
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
        return 'BlMotCleErcType';
    }

}