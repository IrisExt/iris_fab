<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrAgFi;
use App\Entity\TrInstFi;
use App\Form\Blocs\Type\BlInstFiType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use \App\Form\Blocs\Type\BlDocScientifiqueType;
use \App\Form\Blocs\Type\BlIdentProjType;
/**
 * Class FormulaireTypeType
 *
 * @package App\Form\Blocs\Type
 */
class FormulaireType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idAgenceFi', BlInstFiType::class, [
//                'data_class' => TgProjet::class,
//                'attr' => array('class' => 'hiddenfield')
//            ])
//            ->add('lbPreproposition', BlDocScientifiqueType::class, [
//                'data_class' => TgProjet::class,
//                'attr' => array('class' => 'hiddenfield')
//            ])
//            ->add('blDemCofi', BlIdentProjType::class, [
//                'data_class' => TgProjet::class,
//                'attr' => array('class' => 'hiddenfield')
//            ])


//            ->add('idInfraFi', HiddenType::class, [
//
//
//            ]);
//
//        $builder
//            ->add('idAgenceFi', HiddenType::class, [
//
//
//            ])



        ->add('saveAll', SubmitType::class, [
            'label' => 'Enregistrment final',
            'attr' => array('class' => 'rightButton')
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'FormulaireType';
    }

}