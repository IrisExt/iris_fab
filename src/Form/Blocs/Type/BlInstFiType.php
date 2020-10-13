<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrAgFi;
use App\Entity\TrInstFi;
use App\Repository\TrAgFiRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Blocs\Type\EntityRepository;
/**
 * Class BlInstFiType
 * @package App\Form\Blocs\Type
 */
class BlInstFiType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idInfraFi', EntityType::class, [
                'class' => TrInstFi::class,
                'placeholder' => 'bloc.form.blinstfi.instrumentHolder',
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blinstfi.instrument',
                'required' => true,
                'attr' => ['class' => 'chosen-select hidden_'.$options['hidden']['idInfraFi']],
                'disabled' => $options['op_disabled']['idInfraFi']
            ]);

            $builder
            ->add('idAgenceFi', EntityType::class, [
            'class' => TrAgFi::class,
            'query_builder' => function (TrAgFiRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lbAgencFi', 'Asc');
            },
            'placeholder' => 'bloc.form.blinstfi.agenceHolder',
            'translation_domain' => 'Blocs',
            'label' => 'bloc.form.blinstfi.cooperation',
            'label_attr' => ['class' => 'PRCI'],
            'required' => false,
            'attr' => ['class' => 'chosen-select PRCI hidden_'.$options['hidden']['idAgenceFi']],
            'disabled' => $options['op_disabled']['idAgenceFi']
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
        return 'BlInstFiType';
    }

}