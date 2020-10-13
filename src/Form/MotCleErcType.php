<?php


namespace App\Form;


use App\Entity\TgMotCleErc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotCleErcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('DataForm', HiddenType::class,[
                'mapped'=> false,
//                'required' => true,
                'label' => false,
//                'attr'=>['class' => 'hidden']
                ]);
//            ->add('idMcErc', EntityType::class, [
//                'class' => TgMotCleErc::class,
////                'translation_domain' => 'Blocs',
//                'label' => false,
//                'required' => false,
//                'multiple' => true,
//                'attr' => ['class' => 'dual_select'],
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgMotCleErc::class,

        ]);
    }

    
}