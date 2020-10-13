<?php


namespace App\Form\Blocs\Type;

use App\Entity\TgProjet;
use App\Entity\TrCoFi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlCofinancementType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blDemCofi', CheckboxType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blcofin.choix_cof',
                'label_attr' => ['class' => 'pull-left'],
                'required' => false,
                'attr' => ['class' => 'btn-group btn-group-toggle hidden_'.$options['hidden']['blDemCofi']],
                'disabled' => $options['op_disabled']['blDemCofi']
            ])
            ->add('idCoFi', EntityType::class, [
                'class' => TrCoFi::class,
                'translation_domain' => 'Blocs',
                'label' => false,
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'dual_select hidden_'.$options['hidden']['blDemCofi']],
                'disabled' => $options['op_disabled']['blDemCofi']
            ]);
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
        return 'BlCofinancementType';
    }
}